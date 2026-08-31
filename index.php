<?php require_once 'contact.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📇 Contact Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <!-- HEADER -->
    <header>
        <h1>📇 Contact Manager</h1>
        <p>Simple &amp; efficient contact management</p>
    </header>

    <!-- MESSAGES -->
    <?php if (isset($_SESSION['msg'])): ?>
        <div class="msg msg-<?= $_SESSION['msg_type'] ?>">
            <?= htmlspecialchars($_SESSION['msg']) ?>
        </div>
        <?php unset($_SESSION['msg'], $_SESSION['msg_type']); ?>
    <?php endif; ?>

    <!-- ADD CONTACT FORM -->
    <div class="card">
        <h2>➕ Add Contact</h2>
        <form method="POST" action="contact.php" class="form-row">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="name" placeholder="Enter name" required>
            </div>
            <div class="form-group">
                <label>Phone *</label>
                <input type="tel" name="phone" placeholder="Enter phone" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter email (optional)">
            </div>
            <button type="submit" name="add" class="btn btn-primary">Add</button>
        </form>
    </div>

    <!-- SEARCH & ACTIONS -->
    <div class="card">
        <div class="actions">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="🔍 Search contacts...">
                <button onclick="doSearch()" class="btn btn-secondary">Search</button>
            </div>
            <button onclick="viewAll()" class="btn btn-info">👁️ All</button>
            <button onclick="doSort('asc')" class="btn btn-warning">⬆️ A-Z</button>
            <button onclick="doSort('desc')" class="btn btn-warning">⬇️ Z-A</button>
            <a href="contact.php?clear=1" class="btn btn-danger" onclick="return confirm('Delete all contacts?')">🗑️ Clear</a>
        </div>
    </div>

    <!-- CONTACT LIST -->
    <div class="card">
        <h2>📋 Contact List</h2>
        <div id="contactList">
            <?php
            $contacts = all();
            if (empty($contacts)) {
                echo '<p class="empty">📭 No contacts found</p>';
            } else {
                echo '<table class="table">';
                echo '<thead><tr>';
                echo '<th>#</th><th>Name</th><th>Phone</th><th>Email</th><th></th>';
                echo '</tr></thead><tbody>';
                foreach ($contacts as $i => $c) {
                    echo '<tr>';
                    echo '<td>' . ($i + 1) . '</td>';
                    echo '<td class="name">' . htmlspecialchars($c['name']) . '</td>';
                    echo '<td>' . htmlspecialchars($c['phone']) . '</td>';
                    echo '<td>' . htmlspecialchars($c['email']) . '</td>';
                    echo '<td>';
                    echo '<form method="POST" action="contact.php" class="delete-form" onsubmit="return confirm(\'Delete this contact?\')">';
                    echo '<input type="hidden" name="del" value="' . htmlspecialchars($c['name']) . '">';
                    echo '<button type="submit" class="btn-delete">✕</button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
                echo '<div class="total">Total: ' . count($contacts) . ' contacts</div>';
            }
            ?>
        </div>
    </div>

    <!-- STATISTICS -->
    <div class="card">
        <h2>📊 Statistics</h2>
        <div id="statistics">
            <?php
            $statsData = stats();
            echo '<div class="stats-grid">';
            echo '<div class="stat"><div class="label">Total</div><div class="value highlight">' . $statsData['total'] . '</div></div>';
            echo '<div class="stat"><div class="label">With Email</div><div class="value green">' . $statsData['with_email'] . '</div></div>';
            echo '<div class="stat"><div class="label">Without Email</div><div class="value red">' . $statsData['without_email'] . '</div></div>';
            echo '<div class="stat"><div class="label">First</div><div class="value name">' . htmlspecialchars($statsData['first']) . '</div></div>';
            echo '<div class="stat"><div class="label">Last</div><div class="value name">' . htmlspecialchars($statsData['last']) . '</div></div>';
            echo '</div>';
            ?>
        </div>
    </div>

    <footer>&copy; 2024 Contact Manager</footer>
</div>

<!-- JAVASCRIPT -->
<script src="script.js"></script>

</body>
</html>