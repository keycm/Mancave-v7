<?php
session_start();
include 'config.php';

// Security Check
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// 1. Fetch Inquiries (EXCLUDING Copy Requests)
$inquiries = [];
$sql_inq = "SELECT * FROM inquiries WHERE message NOT LIKE '%requesting a copy%' ORDER BY created_at DESC";
if ($res_inq = mysqli_query($conn, $sql_inq)) {
    while ($row = mysqli_fetch_assoc($res_inq)) {
        $inquiries[] = $row;
    }
}

// 2. Fetch Ratings
$ratings = [];
$sql_rate = "SELECT r.*, u.username, s.name as service_name 
             FROM ratings r 
             LEFT JOIN users u ON r.user_id = u.id 
             LEFT JOIN services s ON r.service_id = s.id 
             ORDER BY r.created_at DESC";
if ($res_rate = mysqli_query($conn, $sql_rate)) {
    while ($row = mysqli_fetch_assoc($res_rate)) {
        $ratings[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback & Inquiries | ManCave Admin</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&family=Playfair+Display:wght@600;700&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin_new_style.css">
    
    <style>
        /* Logo Styles (From Homepage) */
        .sidebar-logo { display: flex; flex-direction: column; align-items: center; gap: 0; line-height: 1; text-decoration: none; }
        .logo-top { font-family: 'Playfair Display', serif; font-size: 0.7rem; font-weight: 700; color: #ccc; letter-spacing: 2px; }
        .logo-main { font-family: 'Pacifico', cursive; font-size: 1.8rem; transform: rotate(-4deg); margin: 5px 0; color: #fff; }
        .logo-red { color: #ff4d4d; }
        .logo-bottom { font-family: 'Nunito Sans', sans-serif; font-size: 0.6rem; font-weight: 800; color: #ccc; letter-spacing: 3px; text-transform: uppercase; }

        /* Notification Styles (From Admin) */
        .header-actions { display: flex; align-items: center; gap: 25px; }
        .notif-wrapper { position: relative; }
        .notif-bell { background: #fff; width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: var(--secondary); cursor: pointer; box-shadow: var(--shadow-sm); border: 1px solid var(--border); transition: 0.3s; }
        .notif-bell:hover { color: var(--accent); transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .notif-bell .dot { position: absolute; top: -2px; right: -2px; background: var(--red); color: white; font-size: 0.65rem; font-weight: 700; border-radius: 50%; min-width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border: 2px solid #fff; }
        .notif-dropdown { display: none; position: absolute; right: -10px; top: 55px; width: 320px; background: var(--white); border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border); z-index: 1100; overflow: hidden; transform-origin: top right; animation: slideDown 0.2s ease-out; }
        .notif-dropdown.active { display: block; }

        /* Page Specific Styles */
        .tabs { margin-bottom: 30px; display: flex; gap: 15px; border-bottom: 2px solid #f0f0f0; padding-bottom: 0; }
        .tab-btn { background: none; border: none; padding: 12px 25px; font-size: 1rem; font-weight: 700; color: var(--secondary); cursor: pointer; border-bottom: 3px solid transparent; margin-bottom: -2px; transition: all 0.3s; }
        .tab-btn:hover { color: var(--primary); }
        .tab-btn.active { color: var(--accent); border-bottom-color: var(--accent); }
        .tab-pane { display: none; animation: fadeIn 0.3s ease; }
        .tab-pane.active { display: block; }

        .user-cell strong { display: block; font-size: 0.95rem; color: var(--primary); }
        .user-cell small { color: var(--secondary); }
        .msg-preview { max-width: 350px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #555; }
        .unread-row { background-color: #fffaf0; }
        .unread-row td { font-weight: 600; color: var(--primary); }
        .stars { color: #f39c12; font-size: 0.85rem; letter-spacing: 2px; }
        .star-empty { color: #e0e0e0; }
        
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; opacity: 0; visibility: hidden; transition: 0.3s; z-index: 2000; }
        .modal-overlay.show { opacity: 1; visibility: visible; }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="index.php" class="sidebar-logo">
                <span class="logo-top">THE</span>
                <span class="logo-main"><span class="logo-red">M</span>an<span class="logo-red">C</span>ave</span>
                <span class="logo-bottom">GALLERY</span>
            </a>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="admin.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li><a href="reservations.php"><i class="fas fa-calendar-check"></i> <span>Appointments</span></a></li>
                <li><a href="content.php"><i class="fas fa-layer-group"></i> <span>Inventory & Services</span></a></li>
                <li><a href="users.php"><i class="fas fa-users"></i> <span>Customers</span></a></li>
                <li class="active"><a href="feedback.php"><i class="fas fa-comments"></i> <span>Feedback & Inquiries</span></a></li>
                <li><a href="trash.php"><i class="fas fa-trash-alt"></i> <span>Recycle Bin</span></a></li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
        </div>
    </aside>

    <main class="main-content">
        <header class="top-bar">
            <div class="page-header">
                <h1>Feedback Center</h1>
                <p>Manage customer inquiries and service reviews.</p>
            </div>
            
            <div class="header-actions">
                <div class="notif-wrapper">
                    <div class="notif-bell" id="adminNotifBtn">
                        <i class="far fa-bell"></i>
                        <span class="dot" id="adminNotifBadge" style="display:none;">0</span>
                    </div>
                    
                    <div class="notif-dropdown" id="adminNotifDropdown">
                        <div class="notif-header">
                            <span>Notifications</span>
                            <button id="adminMarkAllRead" class="small-btn">Mark all read</button>
                        </div>
                        <ul class="notif-list" id="adminNotifList">
                            <li class="no-notif">Loading...</li>
                        </ul>
                    </div>
                </div>

                <div class="user-profile">
                    <div class="profile-info">
                        <span class="name">Administrator</span>
                        <span class="role">Super Admin</span>
                    </div>
                    <div class="avatar"><img src="https://ui-avatars.com/api/?name=Admin&background=cd853f&color=fff" alt="Admin"></div>
                </div>
            </div>
        </header>

        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('inquiries', this)"><i class="fas fa-envelope"></i> General Inquiries</button>
            <button class="tab-btn" onclick="switchTab('ratings', this)"><i class="fas fa-star"></i> Service Ratings</button>
        </div>

        <div id="inquiries" class="tab-pane active">
            <div class="card table-card">
                <div class="card-header"><h3>Customer Messages</h3></div>
                <div class="table-responsive">
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th style="width: 25%;">User Info</th>
                                <th style="width: 40%;">Message Preview</th>
                                <th style="width: 15%;">Status</th>
                                <th style="width: 10%;">Date</th>
                                <th style="width: 10%; text-align:right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($inquiries)): ?>
                                <tr><td colspan="5" class="text-center" style="padding:50px; color:#999;">No general inquiries found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($inquiries as $inq): 
                                    $isUnread = ($inq['status'] !== 'read' && $inq['status'] !== 'replied');
                                    $statusClass = 'status-' . ($inq['status'] == 'replied' ? 'completed' : ($isUnread ? 'pending' : 'approved'));
                                    $statusLabel = ($inq['status'] == 'replied') ? 'Replied' : ($isUnread ? 'Unread' : 'Read');
                                ?>
                                <tr class="<?= $isUnread ? 'unread-row' : '' ?>">
                                    <td>
                                        <div class="user-cell">
                                            <strong><?= htmlspecialchars($inq['username']) ?></strong>
                                            <small><?= htmlspecialchars($inq['email']) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="msg-preview"><?= htmlspecialchars($inq['message']) ?></div>
                                    </td>
                                    <td><span class="status-badge <?= $statusClass ?>"><?= $statusLabel ?></span></td>
                                    <td><?= date('M d, Y', strtotime($inq['created_at'])) ?></td>
                                    <td style="text-align:right;">
                                        <div class="actions" style="justify-content: flex-end;">
                                            <button class="btn-icon edit" onclick="viewInquiry(<?= $inq['id'] ?>)" title="View & Reply">
                                                <i class="fas fa-envelope-open-text"></i>
                                            </button>
                                            <button class="btn-icon delete" onclick="deleteInquiry(<?= $inq['id'] ?>)" title="Move to Trash">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="ratings" class="tab-pane">
            <div class="card table-card">
                <div class="card-header"><h3>Service Reviews</h3></div>
                <div class="table-responsive">
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Service Rated</th>
                                <th>Rating</th>
                                <th>Review</th>
                                <th style="text-align:right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($ratings)): ?>
                                <tr><td colspan="5" class="text-center" style="padding:50px; color:#999;">No ratings yet.</td></tr>
                            <?php else: ?>
                                <?php foreach ($ratings as $rate): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($rate['username'] ?? 'Guest') ?></strong></td>
                                    <td><?= htmlspecialchars($rate['service_name'] ?? 'General Service') ?></td>
                                    <td>
                                        <div class="stars">
                                            <?php for($i=1; $i<=5; $i++): ?>
                                                <i class="fas fa-star <?= $i <= $rate['rating'] ? '' : 'star-empty' ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td><div class="msg-preview"><?= htmlspecialchars($rate['review']) ?></div></td>
                                    <td style="text-align:right;">
                                        <button class="btn-icon delete" onclick="deleteRating(<?= $rate['id'] ?>)" title="Delete Review">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <div class="modal-overlay" id="inquiryModal">
        <div style="background: #fff; width: 650px; max-width: 95%; border-radius: 12px; box-shadow: 0 15px 50px rgba(0,0,0,0.3); overflow: hidden; animation: slideUp 0.3s ease-out; display: flex; flex-direction: column; position: relative;">
            <div style="padding: 20px 25px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; background: #fcfcfc;">
                <h3 style="margin:0; color: #2c3e50; font-family: 'Playfair Display', serif;">Inquiry Details</h3>
                <button onclick="closeModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #999; transition: 0.2s;">&times;</button>
            </div>
            <div style="padding: 25px; max-height: 75vh; overflow-y: auto;">
                <div id="inquiryContent" style="background: #f8f9fa; border: 1px solid #eee; border-radius: 8px; padding: 20px; margin-bottom: 25px;">
                    <p style="text-align:center; color:#888;">Loading details...</p>
                </div>
                <div>
                    <h4 style="margin: 0 0 15px 0; color: #cd853f; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px;">Reply to Customer</h4>
                    <form id="replyForm">
                        <input type="hidden" id="replyId" name="id">
                        <div style="margin-bottom: 15px;">
                            <label style="display:block; font-weight:700; font-size:0.85rem; color:#555; margin-bottom:5px;">Subject</label>
                            <input type="text" name="subject" value="Re: Your Inquiry - ManCave Gallery" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; outline: none; transition: 0.3s;">
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display:block; font-weight:700; font-size:0.85rem; color:#555; margin-bottom:5px;">Message</label>
                            <textarea name="message" rows="5" placeholder="Type your reply here..." required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; font-family: inherit; outline: none; resize: vertical;"></textarea>
                        </div>
                        <button type="submit" style="width: 100%; background: #cd853f; color: white; padding: 14px; border: none; border-radius: 6px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: 0.3s; display: flex; justify-content: center; align-items: center; gap: 10px;">Send Reply <i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- TABS ---
        function switchTab(tabId, btn) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        }

        // --- NOTIFICATION LOGIC (Copied from Admin) ---
        document.addEventListener('DOMContentLoaded', () => {
            const notifBtn = document.getElementById('adminNotifBtn');
            const notifDropdown = document.getElementById('adminNotifDropdown');
            const notifBadge = document.getElementById('adminNotifBadge');
            const notifList = document.getElementById('adminNotifList');
            const markAllBtn = document.getElementById('adminMarkAllRead');

            // 1. Toggle Dropdown
            notifBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                notifDropdown.classList.toggle('active');
            });

            // 2. Fetch Notifications
            function fetchNotifications() {
                fetch('fetch_notifications.php')
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            if (data.unread_count > 0) {
                                notifBadge.innerText = data.unread_count;
                                notifBadge.style.display = 'flex';
                            } else {
                                notifBadge.style.display = 'none';
                            }

                            notifList.innerHTML = '';
                            if (data.notifications.length === 0) {
                                notifList.innerHTML = '<li class="no-notif">No new notifications</li>';
                            } else {
                                data.notifications.forEach(notif => {
                                    const li = document.createElement('li');
                                    li.className = `notif-item ${notif.is_read == 0 ? 'unread' : ''}`;
                                    li.innerHTML = `
                                        <div class="notif-msg">${notif.message}</div>
                                        <div class="notif-time">${notif.created_at}</div>
                                    `;
                                    li.addEventListener('click', () => {
                                        const formData = new FormData();
                                        formData.append('id', notif.id);
                                        fetch('mark_as_read.php', { method: 'POST', body: formData })
                                            .then(() => fetchNotifications());
                                    });
                                    notifList.appendChild(li);
                                });
                            }
                        }
                    })
                    .catch(err => console.error(err));
            }

            if (markAllBtn) {
                markAllBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    fetch('mark_all_as_read.php', { method: 'POST' })
                        .then(() => fetchNotifications());
                });
            }

            window.addEventListener('click', () => {
                if (notifDropdown.classList.contains('active')) {
                    notifDropdown.classList.remove('active');
                }
            });
            notifDropdown.addEventListener('click', (e) => e.stopPropagation());

            fetchNotifications();
            setInterval(fetchNotifications, 30000);
        });

        // --- INQUIRY LOGIC ---
        const modal = document.getElementById('inquiryModal');
        function closeModal() { modal.classList.remove('show'); }

        function viewInquiry(id) {
            modal.classList.add('show');
            document.getElementById('inquiryContent').innerHTML = '<p style="text-align:center; color:#888;">Loading...</p>';
            document.getElementById('replyId').value = id;

            fetch(`get_inquiry.php?id=${id}`)
                .then(res => res.json())
                .then(data => {
                    if(data.error) {
                        document.getElementById('inquiryContent').innerHTML = `<p style="color:red;">${data.error}</p>`;
                    } else {
                        document.getElementById('inquiryContent').innerHTML = `
                            <div style="display:flex; justify-content:space-between; border-bottom:1px solid #eee; padding-bottom:10px; margin-bottom:10px;">
                                <div>
                                    <span style="display:block; font-size:0.85rem; color:#888; text-transform:uppercase; letter-spacing:0.5px;">From</span>
                                    <strong style="font-size:1.1rem; color:#2c3e50;">${data.username}</strong>
                                    <div style="color:#666; font-size:0.9rem;">${data.email}</div>
                                </div>
                                <div style="text-align:right;">
                                    <span style="display:block; font-size:0.85rem; color:#888; text-transform:uppercase; letter-spacing:0.5px;">Date</span>
                                    <div style="font-weight:600; color:#555;">${data.created_at}</div>
                                </div>
                            </div>
                            <div style="margin-bottom:10px;">
                                <span style="font-size:0.85rem; color:#888; font-weight:700;">Contact:</span> 
                                <span style="color:#333;">${data.mobile}</span>
                            </div>
                            <div style="margin-top:15px;">
                                <span style="display:block; font-size:0.85rem; color:#888; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:5px;">Message</span>
                                <p style="line-height:1.6; color:#333; white-space: pre-wrap; margin:0;">${data.message}</p>
                            </div>
                        `;
                    }
                });
        }

        // Reply Form
        document.getElementById('replyForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const btn = e.target.querySelector('button');
            const originalText = btn.innerHTML;
            btn.disabled = true; btn.innerHTML = 'Sending...';

            try {
                const res = await fetch('reply_inquiry.php', { method: 'POST', body: formData });
                const text = await res.text();
                if(text.trim() === 'success') {
                    alert('Reply sent successfully!');
                    closeModal();
                    location.reload();
                } else {
                    alert('Error sending email. Please try again.');
                }
            } catch(err) { alert('Request failed.'); } 
            finally { btn.disabled = false; btn.innerHTML = originalText; }
        });

        // Delete Inquiry (Sends to Trash)
        function deleteInquiry(id) {
            if(confirm('Are you sure you want to move this inquiry to the trash?')) {
                const formData = new FormData();
                formData.append('id', id);
                fetch('delete_inquiry.php', { method: 'POST', body: formData })
                    .then(res => res.json())
                    .then(data => {
                        if(data.status === 'success') location.reload();
                        else alert('Error: ' + data.message);
                    });
            }
        }

        // Delete Rating (Sends to Trash)
        function deleteRating(id) {
            if(confirm('Permanently delete this review?')) {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('action', 'delete_rating');
                fetch('manage_feedback.php', { method: 'POST', body: formData })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) location.reload();
                        else alert('Error deleting rating.');
                    });
            }
        }

        window.onclick = function(event) {
            if (event.target == modal) closeModal();
        }
    </script>
</body>
</html>