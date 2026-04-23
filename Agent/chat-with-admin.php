<?php
session_start();
if(!isset($_SESSION['agent_id'])) { 
    header('location: index.php'); 
    exit(); 
}

// Include database config
if(file_exists('../includes/config.php')) {
    include('../includes/config.php');
} else {
    die('Configuration file not found.');
}

if(!$con) {
    die('Database connection failed: ' . mysqli_connect_error());
}

$agent_id = intval($_SESSION['agent_id']);

// Increase PHP limits to allow larger files (1GB max)
ini_set('upload_max_filesize', '1024M');
ini_set('post_max_size', '1025M');
ini_set('max_execution_time', 600);
ini_set('memory_limit', '2048M');

// Ensure the messages table has file columns
$columns = $con->query("SHOW COLUMNS FROM admin_agent_messages");
$existing = [];
while($col = $columns->fetch_assoc()) {
    $existing[] = $col['Field'];
}
if(!in_array('file_path', $existing)) {
    $con->query("ALTER TABLE admin_agent_messages ADD COLUMN file_path VARCHAR(500) DEFAULT NULL");
}
if(!in_array('file_type', $existing)) {
    $con->query("ALTER TABLE admin_agent_messages ADD COLUMN file_type VARCHAR(50) DEFAULT NULL");
}

// Create upload directory if not exists + security .htaccess
$upload_dir = '../uploads/agent_chats/';
if(!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
    file_put_contents($upload_dir . '.htaccess', "Options -Indexes\n<FilesMatch \"\\.(php|phtml|php3|php4|php5|phps|cgi|pl|asp|aspx|shtml|shtm|py|rb|sh)$\">\n    Order Deny,Allow\n    Deny from all\n</FilesMatch>");
}

// ========== AJAX HANDLERS ==========
if(isset($_GET['get_messages'])) {
    $agent_id_ajax = intval($_GET['agent_id'] ?? $agent_id);
    $messages = $con->query("SELECT * FROM admin_agent_messages WHERE agent_id = $agent_id_ajax ORDER BY created_at ASC");
    while($msg = $messages->fetch_assoc()) {
        $is_agent = ($msg['sender_type'] == 'agent');
        $time = date('H:i', strtotime($msg['created_at']));
        $file_html = '';
        
        if(!empty($msg['file_path'])) {
            // Determine correct file location and web URL
            $stored_path = $msg['file_path'];
            $found_path = null;
            
            // Try different possible locations
            $possible_paths = [
                '../' . $stored_path,                           // agent's own uploads (root/uploads/agent_chats/)
                '../../admin/' . $stored_path,                  // admin's uploads (admin/uploads/agent_chats/)
                '../admin/uploads/agent_chats/' . basename($stored_path), // alternative admin path
                $stored_path                                    // direct fallback
            ];
            
            foreach($possible_paths as $path) {
                if(file_exists($path)) {
                    $found_path = $path;
                    break;
                }
            }
            
            if($found_path) {
                $mime = $msg['file_type'];
                if(strpos($mime, 'image/') === 0) {
                    $file_html = '<div class="file-attachment media-item" data-src="' . htmlspecialchars($found_path) . '" data-type="image"><img src="' . htmlspecialchars($found_path) . '" loading="lazy"></div>';
                } elseif(strpos($mime, 'video/') === 0) {
                    $file_html = '<div class="file-attachment media-item" data-src="' . htmlspecialchars($found_path) . '" data-type="video"><video controls src="' . htmlspecialchars($found_path) . '"></video></div>';
                } elseif(strpos($mime, 'audio/') === 0) {
                    $file_html = '<div class="file-attachment"><audio controls src="' . htmlspecialchars($found_path) . '"></audio></div>';
                }
            } else {
                error_log("File not found for agent: " . $stored_path);
            }
        }
        
        $message_text = nl2br(htmlspecialchars($msg['message']));
        echo '<div class="message ' . ($is_agent ? 'agent-message' : 'admin-message') . '">
                <div class="message-bubble">
                    ' . $message_text . $file_html . '
                    <div class="message-time">' . $time . '</div>
                </div>
              </div>';
    }
    exit;
}

if(isset($_POST['action']) && $_POST['action'] == 'send_message_with_file') {
    $agent_id_post = intval($_POST['agent_id']);
    $message = trim($_POST['message'] ?? '');
    $file_path = null;
    $file_type = null;
    $response = ['status' => 'error', 'message' => 'Unknown error'];
    
    if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $maxSize = 1073741824; // 1GB
        if($_FILES['file']['size'] > $maxSize) {
            echo json_encode(['status' => 'error', 'message' => 'File too large. Maximum size is 1GB.']);
            exit;
        }
        
        $allowed = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'video/mp4', 'video/webm', 'video/ogg', 'video/quicktime', 'video/x-msvideo',
            'audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/webm'
        ];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['file']['tmp_name']);
        finfo_close($finfo);
        
        if(in_array($mime, $allowed)) {
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $filename = 'agent_' . $agent_id_post . '_' . time() . '_' . rand(1000,9999) . '.' . $ext;
            $destination = $upload_dir . $filename;
            if(move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
                $file_path = 'uploads/agent_chats/' . $filename; // stored as relative to project root
                $file_type = $mime;
                $response['status'] = 'ok';
            } else {
                $response['message'] = 'Failed to move uploaded file. Check folder permissions.';
            }
        } else {
            $response['message'] = 'Invalid file type. Only images, videos, and audio are allowed.';
        }
    } else {
        $response['status'] = 'ok';
    }
    
    if($response['status'] == 'ok') {
        $stmt = $con->prepare("INSERT INTO admin_agent_messages (agent_id, admin_id, sender_type, message, file_path, file_type) VALUES (?, NULL, 'agent', ?, ?, ?)");
        $stmt->bind_param("isss", $agent_id_post, $message, $file_path, $file_type);
        $stmt->execute();
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode($response);
    }
    exit;
}

// Mark messages from admin as read
$con->query("UPDATE admin_agent_messages SET is_read = 1 WHERE agent_id = $agent_id AND sender_type = 'admin' AND is_read = 0");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Chat with Admin | Agent Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.12/dist/cropper.min.css" rel="stylesheet">
    <style>
        body { background: #FCF9F5; font-family: 'Inter', sans-serif; }
        .admin-wrapper { display: flex; flex-direction: column; min-height: 100vh; }
        .admin-content-wrapper { display: flex; flex: 1; }
        .main-content { flex: 1; padding: 20px 24px; background: #FCF9F5; }
        .chat-container { max-width: 800px; margin: 0 auto; background: white; border-radius: 28px; border: 1px solid #EFE8E2; overflow: hidden; display: flex; flex-direction: column; height: calc(100vh - 180px); }
        .chat-header { background: #C47A5E; color: white; padding: 1rem; }
        .chat-messages { flex: 1; overflow-y: auto; padding: 1rem; background: #FCF9F5; display: flex; flex-direction: column; }
        .message { display: flex; margin-bottom: 1rem; }
        .agent-message { justify-content: flex-end; }
        .admin-message { justify-content: flex-start; }
        .message-bubble { max-width: 70%; padding: 0.6rem 1rem; border-radius: 20px; font-size: 0.85rem; line-height: 1.4; word-wrap: break-word; }
        .agent-message .message-bubble { background: #C47A5E; color: white; border-bottom-right-radius: 4px; }
        .admin-message .message-bubble { background: #F0E9E3; color: #2A2826; border-bottom-left-radius: 4px; }
        .message-time { font-size: 0.65rem; margin-top: 4px; text-align: right; color: #7A726C; }
        .agent-message .message-time { color: #E3DCD5; }
        .file-attachment { display: block; margin-top: 8px; cursor: pointer; }
        .file-attachment img, .file-attachment video { max-width: 200px; max-height: 150px; border-radius: 12px; }
        .file-attachment audio { width: 200px; cursor: default; }
        .chat-input-area { display: flex; flex-direction: column; border-top: 1px solid #EFE8E2; background: white; }
        .chat-input { display: flex; padding: 1rem; gap: 0.5rem; align-items: center; }
        .chat-input input[type="text"] { flex: 1; border-radius: 40px; border: 1px solid #E0D6CE; padding: 0.6rem 1rem; }
        .attach-btn, .mic-btn { background: #F0E9E3; border: none; border-radius: 40px; width: 40px; height: 40px; color: #C47A5E; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; }
        .send-btn { background: #C47A5E; border: none; border-radius: 40px; padding: 0 1.5rem; color: white; font-weight: 600; height: 40px; }
        .file-preview { padding: 0.5rem 1rem; background: #FCF9F5; border-top: 1px solid #EFE8E2; display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
        .file-preview .preview-thumb { max-width: 80px; max-height: 60px; border-radius: 8px; }
        .preview-info { font-size: 0.75rem; color: #4A4440; }
        .remove-file, .edit-file { color: #C47A5E; cursor: pointer; margin-left: 8px; }
        .remove-file:hover { color: #C62828; }
        .recording-indicator { color: #C62828; animation: blink 1s infinite; }
        @keyframes blink { 50% { opacity: 0.5; } }
        .lightbox-modal { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.9); z-index: 9999; justify-content: center; align-items: center; }
        .lightbox-content { max-width: 90%; max-height: 90%; }
        .lightbox-content img, .lightbox-content video { max-width: 100%; max-height: 100%; object-fit: contain; }
        .close-lightbox { position: absolute; top: 20px; right: 40px; color: white; font-size: 40px; cursor: pointer; }
        .back-link { display: inline-block; margin-bottom: 1rem; color: #C47A5E; text-decoration: none; }
        @media (max-width: 768px) { .chat-container { height: calc(100vh - 140px); } }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <?php include('includes/header.php'); ?>
    <div class="admin-content-wrapper">
        <div class="col-md-3 col-lg-2"><?php include('includes/sidebar.php'); ?></div>
        <div class="col-md-9 col-lg-10 main-content">
            <div class="px-4 pt-3">
                <a href="dashboard.php" class="back-link"><i class="fas fa-arrow-left"></i> Dashboard</a>
                <div class="chat-container">
                    <div class="chat-header">
                        <h5 class="mb-0"><i class="fas fa-user-shield"></i> Chat with Admin</h5>
                    </div>
                    <div class="chat-messages" id="chatMessages"></div>
                    <div class="chat-input-area">
                        <div id="filePreviewContainer" class="file-preview" style="display: none;"></div>
                        <div class="chat-input">
                            <input type="text" id="messageInput" placeholder="Type a message...">
                            <label for="fileInput" class="attach-btn"><i class="fas fa-paperclip"></i></label>
                            <input type="file" id="fileInput" style="display:none" accept="image/*,video/*,audio/*">
                            <button class="mic-btn" id="micBtn"><i class="fas fa-microphone"></i></button>
                            <button class="send-btn" onclick="sendMessageWithFile()"><i class="fas fa-paper-plane"></i> Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('includes/footer.php'); ?>
</div>

<!-- Lightbox Modal -->
<div id="lightbox" class="lightbox-modal" onclick="closeLightbox()">
    <span class="close-lightbox">&times;</span>
    <div class="lightbox-content" id="lightboxContent"></div>
</div>

<!-- Image Editing Modal -->
<div class="modal fade" id="imageEditorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"><h5>Edit Image</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="img-container"><img id="editImage" style="max-width:100%;"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="rotateLeft">Rotate Left</button>
                <button class="btn btn-secondary" id="rotateRight">Rotate Right</button>
                <button class="btn btn-primary" id="applyCrop">Apply</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.12/dist/cropper.min.js"></script>
<script>
var agent_id = <?php echo $agent_id; ?>;
var refreshInterval = null;
var selectedFile = null;
var cropper = null;
var mediaRecorder = null;
var audioChunks = [];
var isRecording = false;

var wasAtBottom = true;

function loadMessages() {
    if(!agent_id) return;
    var chatDiv = $('#chatMessages');
    var scrollTop = chatDiv.scrollTop();
    var scrollHeight = chatDiv[0].scrollHeight;
    var clientHeight = chatDiv[0].clientHeight;
    wasAtBottom = (scrollHeight - scrollTop - clientHeight) < 50;
    
    $.get(window.location.pathname, { get_messages: 1, agent_id: agent_id, t: Date.now() }, function(data) {
        chatDiv.html(data);
        var newScrollHeight = chatDiv[0].scrollHeight;
        if (wasAtBottom) {
            chatDiv.scrollTop(newScrollHeight);
        } else {
            var delta = newScrollHeight - scrollHeight;
            chatDiv.scrollTop(scrollTop + delta);
        }
        $('.media-item').off('click').on('click', function(e) {
            e.stopPropagation();
            var src = $(this).data('src');
            var type = $(this).data('type');
            showLightbox(src, type);
        });
    }).fail(function() { console.log('Error loading messages'); });
}

function showLightbox(src, type) {
    var content = '';
    if(type === 'image') {
        content = '<img src="' + src + '">';
    } else if(type === 'video') {
        content = '<video controls autoplay src="' + src + '"></video>';
    }
    $('#lightboxContent').html(content);
    $('#lightbox').fadeIn();
}
function closeLightbox() { $('#lightbox').fadeOut(); $('#lightboxContent').empty(); }

$('#fileInput').on('change', function(e) {
    var file = e.target.files[0];
    if(!file) return;
    if(file.size > 1073741824) {
        alert('File too large. Maximum size is 1GB.');
        $('#fileInput').val('');
        return;
    }
    selectedFile = file;
    var container = $('#filePreviewContainer');
    container.empty().show();
    var previewHtml = '<div class="d-flex align-items-center gap-2">';
    
    if(file.type.startsWith('image/')) {
        var reader = new FileReader();
        reader.onload = function(e) {
            previewHtml += '<img src="' + e.target.result + '" class="preview-thumb">';
            previewHtml += '<div class="preview-info">' + file.name + ' (' + (file.size/1024/1024).toFixed(2) + ' MB)</div>';
            previewHtml += '<i class="fas fa-edit edit-file" onclick="editImage()" title="Edit Image"></i>';
            previewHtml += '<i class="fas fa-times-circle remove-file" onclick="clearFilePreview()"></i>';
            previewHtml += '</div>';
            container.html(previewHtml);
            window.previewImageData = e.target.result;
        };
        reader.readAsDataURL(file);
    } else if(file.type.startsWith('video/')) {
        var videoUrl = URL.createObjectURL(file);
        previewHtml += '<video controls class="preview-thumb" style="max-width:120px; max-height:80px;"><source src="' + videoUrl + '"></video>';
        previewHtml += '<div class="preview-info">' + file.name + ' (' + (file.size/1024/1024).toFixed(2) + ' MB)</div>';
        previewHtml += '<i class="fas fa-times-circle remove-file" onclick="clearFilePreview()"></i></div>';
        container.html(previewHtml);
        URL.revokeObjectURL(videoUrl);
    } else if(file.type.startsWith('audio/')) {
        var audioUrl = URL.createObjectURL(file);
        previewHtml += '<audio controls style="width:150px;"><source src="' + audioUrl + '"></audio>';
        previewHtml += '<div class="preview-info">' + file.name + ' (' + (file.size/1024).toFixed(1) + ' KB)</div>';
        previewHtml += '<i class="fas fa-times-circle remove-file" onclick="clearFilePreview()"></i></div>';
        container.html(previewHtml);
        URL.revokeObjectURL(audioUrl);
    } else {
        previewHtml += '<div class="preview-info">' + file.name + '</div>';
        previewHtml += '<i class="fas fa-times-circle remove-file" onclick="clearFilePreview()"></i></div>';
        container.html(previewHtml);
    }
});

function clearFilePreview() {
    selectedFile = null;
    $('#fileInput').val('');
    $('#filePreviewContainer').hide().empty();
}

function editImage() {
    if(!window.previewImageData) return;
    $('#editImage').attr('src', window.previewImageData);
    var modal = new bootstrap.Modal(document.getElementById('imageEditorModal'));
    modal.show();
    $('#editImage').on('shown.bs.modal', function() {
        if(cropper) cropper.destroy();
        cropper = new Cropper(this, { aspectRatio: NaN, viewMode: 1 });
    });
}
$('#rotateLeft').click(function() { if(cropper) cropper.rotate(-90); });
$('#rotateRight').click(function() { if(cropper) cropper.rotate(90); });
$('#applyCrop').click(function() {
    if(cropper) {
        var canvas = cropper.getCroppedCanvas();
        canvas.toBlob(function(blob) {
            selectedFile = new File([blob], 'edited_image.jpg', { type: 'image/jpeg' });
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#filePreviewContainer .preview-thumb').attr('src', e.target.result);
                window.previewImageData = e.target.result;
            };
            reader.readAsDataURL(blob);
            bootstrap.Modal.getInstance(document.getElementById('imageEditorModal')).hide();
        }, 'image/jpeg');
    }
});

const micBtn = document.getElementById('micBtn');
micBtn.addEventListener('click', toggleRecording);
let audioStream = null;

function toggleRecording() {
    if(isRecording) {
        stopRecording();
    } else {
        startRecording();
    }
}

function startRecording() {
    navigator.mediaDevices.getUserMedia({ audio: true })
        .then(stream => {
            audioStream = stream;
            mediaRecorder = new MediaRecorder(stream);
            audioChunks = [];
            mediaRecorder.ondataavailable = event => audioChunks.push(event.data);
            mediaRecorder.onstop = () => {
                const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                if(audioBlob.size > 0) {
                    selectedFile = new File([audioBlob], 'recording.webm', { type: 'audio/webm' });
                    var container = $('#filePreviewContainer');
                    container.empty().show();
                    var audioUrl = URL.createObjectURL(audioBlob);
                    container.html('<div class="d-flex align-items-center gap-2"><audio controls style="width:150px;"><source src="' + audioUrl + '"></audio><div class="preview-info">Recording (' + (audioBlob.size/1024).toFixed(1) + ' KB)</div><i class="fas fa-times-circle remove-file" onclick="clearFilePreview()"></i></div>');
                    URL.revokeObjectURL(audioUrl);
                } else {
                    alert('No audio recorded.');
                }
                audioStream.getTracks().forEach(track => track.stop());
            };
            mediaRecorder.start();
            isRecording = true;
            micBtn.innerHTML = '<i class="fas fa-stop recording-indicator"></i>';
            micBtn.style.background = '#C62828';
        })
        .catch(err => alert('Microphone access denied: ' + err));
}

function stopRecording() {
    if(mediaRecorder && isRecording) {
        mediaRecorder.stop();
        isRecording = false;
        micBtn.innerHTML = '<i class="fas fa-microphone"></i>';
        micBtn.style.background = '';
    }
}

function sendMessageWithFile() {
    var msg = $('#messageInput').val();
    if(msg.trim() === '' && !selectedFile) return;
    
    var formData = new FormData();
    formData.append('action', 'send_message_with_file');
    formData.append('agent_id', agent_id);
    formData.append('message', msg);
    if(selectedFile) formData.append('file', selectedFile);
    
    $.ajax({
        url: window.location.pathname,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            try {
                var res = JSON.parse(response);
                if(res.status === 'ok') {
                    $('#messageInput').val('');
                    clearFilePreview();
                    loadMessages();
                } else {
                    alert('Error: ' + (res.message || 'Unknown error'));
                }
            } catch(e) {
                console.log('Response:', response);
                alert('Message sent, but could not parse response.');
                $('#messageInput').val('');
                clearFilePreview();
                loadMessages();
            }
        },
        error: function(xhr, status, error) {
            alert('Failed to send: ' + error);
        }
    });
}

refreshInterval = setInterval(loadMessages, 3000);
loadMessages();

$(window).on('beforeunload', function() {
    if(refreshInterval) clearInterval(refreshInterval);
    if(audioStream) audioStream.getTracks().forEach(track => track.stop());
});
</script>
</body>
</html>