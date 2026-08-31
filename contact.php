<?php
// ============================================
// CONTACT MANAGEMENT - BACKEND
// ============================================

session_start();

// ---------- DEFAULT CONTACTS ----------
if (!isset($_SESSION['contacts'])) {
    $_SESSION['contacts'] = [
        ['name' => 'Ali Khan', 'phone' => '0300-1234567', 'email' => 'ali@email.com'],
        ['name' => 'Sara Ahmed', 'phone' => '0301-7654321', 'email' => 'sara@email.com'],
        ['name' => 'Usman Malik', 'phone' => '0302-9876543', 'email' => 'usman@email.com']
    ];
}

// ============================================
// CORE FUNCTIONS
// ============================================

// Get all contacts
function all() {
    return $_SESSION['contacts'];
}

// Add contact
function add($name, $phone, $email = 'N/A') {
    $name = trim($name);
    $phone = trim($phone);
    $email = trim($email) ?: 'N/A';
    
    if (empty($name) || empty($phone)) {
        return ['error' => 'Name and Phone required'];
    }
    
    foreach ($_SESSION['contacts'] as $c) {
        if (strcasecmp($c['name'], $name) === 0) {
            return ['error' => 'Contact already exists'];
        }
    }
    
    $_SESSION['contacts'][] = [
        'name' => $name,
        'phone' => $phone,
        'email' => $email
    ];
    
    return ['success' => 'Contact added'];
}

// Delete contact
function del($name) {
    foreach ($_SESSION['contacts'] as $key => $c) {
        if (strcasecmp($c['name'], trim($name)) === 0) {
            unset($_SESSION['contacts'][$key]);
            $_SESSION['contacts'] = array_values($_SESSION['contacts']);
            return ['success' => 'Contact deleted'];
        }
    }
    return ['error' => 'Contact not found'];
}

// Search contacts
function search($query) {
    if (empty($query)) {
        return $_SESSION['contacts'];
    }
    
    $results = [];
    foreach ($_SESSION['contacts'] as $c) {
        if (stripos($c['name'], $query) !== false) {
            $results[] = $c;
        }
    }
    return $results;
}

// Sort contacts
function sortBy($order = 'asc') {
    $contacts = $_SESSION['contacts'];
    usort($contacts, function($a, $b) use ($order) {
        if ($order === 'asc') {
            return strcasecmp($a['name'], $b['name']);
        } else {
            return strcasecmp($b['name'], $a['name']);
        }
    });
    $_SESSION['contacts'] = $contacts;
    return $contacts;
}

// Clear all
function clear() {
    $_SESSION['contacts'] = [];
    return ['success' => 'All contacts cleared'];
}

// Get statistics
function stats() {
    $contacts = $_SESSION['contacts'];
    $names = array_column($contacts, 'name');
    sort($names);
    
    $withEmail = 0;
    foreach ($contacts as $c) {
        if ($c['email'] !== 'N/A' && !empty($c['email'])) {
            $withEmail++;
        }
    }
    
    return [
        'total' => count($contacts),
        'with_email' => $withEmail,
        'without_email' => count($contacts) - $withEmail,
        'first' => $names[0] ?? 'N/A',
        'last' => end($names) ?: 'N/A'
    ];
}

// ============================================
// AJAX HANDLER
// ============================================

if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    
    $action = $_GET['action'] ?? 'view';
    $data = [];
    
    switch ($action) {
        case 'search':
            $data = search($_GET['q'] ?? '');
            break;
        case 'sort':
            $data = sortBy($_GET['by'] ?? 'asc');
            break;
        case 'clear':
            clear();
            $data = all();
            break;
        default:
            $data = all();
    }
    
    // Build HTML for contacts
    $listHtml = buildList($data);
    
    // Build HTML for statistics
    $statsData = stats();
    $statsHtml = buildStats($statsData);
    
    echo json_encode([
        'list' => $listHtml,
        'stats' => $statsHtml
    ]);
    exit;
}

// ============================================
// HTML BUILDERS
// ============================================

function buildList($data) {
    if (empty($data)) {
        return '<p class="empty">📭 No contacts found</p>';
    }
    
    $html = '<table class="table">';
    $html .= '<thead><tr>';
    $html .= '<th>#</th><th>Name</th><th>Phone</th><th>Email</th><th></th>';
    $html .= '</tr></thead><tbody>';
    
    foreach ($data as $i => $c) {
        $html .= '<tr>';
        $html .= '<td>' . ($i + 1) . '</td>';
        $html .= '<td class="name">' . htmlspecialchars($c['name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($c['phone']) . '</td>';
        $html .= '<td>' . htmlspecialchars($c['email']) . '</td>';
        $html .= '<td>';
        $html .= '<form method="POST" class="delete-form" onsubmit="return confirm(\'Delete this contact?\')">';
        $html .= '<input type="hidden" name="del" value="' . htmlspecialchars($c['name']) . '">';
        $html .= '<button type="submit" class="btn-delete">✕</button>';
        $html .= '</form>';
        $html .= '</td>';
        $html .= '</tr>';
    }
    
    $html .= '</tbody></table>';
    $html .= '<div class="total">Total: ' . count($data) . ' contacts</div>';
    
    return $html;
}

function buildStats($data) {
    return '
    <div class="stats-grid">
        <div class="stat">
            <div class="label">Total</div>
            <div class="value highlight">' . $data['total'] . '</div>
        </div>
        <div class="stat">
            <div class="label">With Email</div>
            <div class="value green">' . $data['with_email'] . '</div>
        </div>
        <div class="stat">
            <div class="label">Without Email</div>
            <div class="value red">' . $data['without_email'] . '</div>
        </div>
        <div class="stat">
            <div class="label">First</div>
            <div class="value name">' . htmlspecialchars($data['first']) . '</div>
        </div>
        <div class="stat">
            <div class="label">Last</div>
            <div class="value name">' . htmlspecialchars($data['last']) . '</div>
        </div>
    </div>';
}

// ============================================
// FORM HANDLING
// ============================================

$message = '';
$msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $result = add($_POST['name'], $_POST['phone'], $_POST['email'] ?? '');
        if (isset($result['success'])) {
            $message = $result['success'];
            $msgType = 'success';
        } else {
            $message = $result['error'];
            $msgType = 'error';
        }
    }
    
    if (isset($_POST['del'])) {
        $result = del($_POST['del']);
        if (isset($result['success'])) {
            $message = $result['success'];
            $msgType = 'success';
        } else {
            $message = $result['error'];
            $msgType = 'error';
        }
    }
    
    // Store message in session
    $_SESSION['msg'] = $message;
    $_SESSION['msg_type'] = $msgType;
    header('Location: index.php');
    exit;
}

// Handle clear via GET
if (isset($_GET['clear'])) {
    $result = clear();
    $_SESSION['msg'] = $result['success'];
    $_SESSION['msg_type'] = 'success';
    header('Location: index.php');
    exit;
}
?>