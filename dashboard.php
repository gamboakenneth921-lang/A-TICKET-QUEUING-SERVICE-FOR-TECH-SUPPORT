<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Tickets</title>
    <link rel="stylesheet" href="style.css">
    <style>
        :root{--bg:#f4f7fb;--card:#fff;--muted:#6b7280;--accent:#2563eb;--ok:#16a34a;--danger:#dc2626}
        *{box-sizing:border-box}
        body{margin:0;font-family:Segoe UI, Roboto, Arial, sans-serif;background:var(--bg);color:#0b1220}

        header{background:linear-gradient(90deg,#0ea5e9,#6366f1);color:#fff;padding:18px 24px;display:flex;justify-content:space-between;align-items:center}
        header h1{margin:0;font-size:18px}
        header .actions{font-size:14px;opacity:.95}

        .wrap{max-width:1200px;margin:20px auto;padding:0 16px;display:grid;grid-template-columns:380px 1fr 300px;gap:18px}

        .card{background:var(--card);border-radius:12px;padding:16px;box-shadow:0 8px 24px rgba(12,20,40,0.06)}

        /* Left - Tickets */
        .tickets .controls{display:flex;gap:8px;margin-bottom:12px}
        .tickets .controls input, .tickets .controls select{flex:1;padding:10px;border-radius:8px;border:1px solid #eef2f7}
        .ticket{display:block;padding:12px;border-radius:10px;border:1px solid #f1f5f9;margin-bottom:10px;text-decoration:none;color:inherit}
        .ticket:hover{box-shadow:0 8px 20px rgba(99,102,241,0.06)}
        .ticket .top{display:flex;justify-content:space-between;align-items:center}
        .title{font-weight:600}
        .meta{font-size:13px;color:var(--muted)}
        .badge{padding:6px 10px;border-radius:999px;color:#fff;font-size:12px}
        .high{background:#ef4444}
        .medium{background:#f59e0b}
        .low{background:#10b981}

        /* Center - Details (static) */
        .details h2{margin-top:0}
        .details .desc{background:#fafbff;padding:12px;border-radius:8px;border:1px solid #eef2ff}
        .details .assign{display:flex;gap:8px;margin-top:12px}
        .details .assign select{flex:1;padding:10px;border-radius:8px;border:1px solid #eef2f7}
        .btn{background:var(--accent);color:#fff;padding:10px 14px;border-radius:8px;border:none;cursor:pointer}

        /* Right - Employees */
        .employees .emp{display:flex;justify-content:space-between;align-items:center;padding:10px;border-radius:8px;border:1px solid #f1f5f9;margin-bottom:10px}
        .status{padding:6px 10px;border-radius:999px;color:#fff;font-size:13px}
        .available{background:var(--ok)}
        .busy{background:var(--danger)}

        footer{max-width:1200px;margin:18px auto;padding:8px 16px;color:var(--muted);font-size:13px}

        @media(max-width:1100px){
            .wrap{grid-template-columns:1fr;padding:0 12px}
            .employees{order:3}
        }
    </style>
</head>
<body>
    <header>
        <h1>Tech Support — Admin Dashboard</h1>
        <div class="actions">Admin view — monitor tickets & assign staff</div>
    </header>

    <main class="wrap">
        <section class="card tickets">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
                <strong>Ticket Queue</strong>
                <span class="meta">4 tickets</span>
            </div>

            <div class="controls">
                <input placeholder="Search tickets..." aria-label="search" />
                <select aria-label="filter">
                    <option>All statuses</option>
                    <option>Open</option>
                    <option>In Progress</option>
                    <option>Resolved</option>
                </select>
            </div>

            <a class="ticket" href="#">
                <div class="top">
                    <div>
                        <div class="title">Cannot connect to VPN</div>
                        <div class="meta">TCK-1001 • 5h ago</div>
                    </div>
                    <div style="text-align:right">
                        <div class="badge high">High</div>
                        <div class="meta" style="margin-top:6px">Open</div>
                    </div>
                </div>
                <div class="meta" style="margin-top:10px">User reports VPN fails with authentication error.</div>
            </a>

            <a class="ticket" href="#">
                <div class="top">
                    <div>
                        <div class="title">Printer offline on 3rd floor</div>
                        <div class="meta">TCK-1002 • 1d ago</div>
                    </div>
                    <div style="text-align:right">
                        <div class="badge medium">Medium</div>
                        <div class="meta" style="margin-top:6px">Open</div>
                    </div>
                </div>
                <div class="meta" style="margin-top:10px">Printer shows offline; restarted but still offline.</div>
            </a>

            <a class="ticket" href="#">
                <div class="top">
                    <div>
                        <div class="title">Email attachments blocked</div>
                        <div class="meta">TCK-1003 • 2h ago</div>
                    </div>
                    <div style="text-align:right">
                        <div class="badge low">Low</div>
                        <div class="meta" style="margin-top:6px">In Progress</div>
                    </div>
                </div>
                <div class="meta" style="margin-top:10px">Attachments larger than 5MB blocked in webmail.</div>
            </a>

            <a class="ticket" href="#">
                <div class="top">
                    <div>
                        <div class="title">Blue screen on startup</div>
                        <div class="meta">TCK-1004 • 3d ago</div>
                    </div>
                    <div style="text-align:right">
                        <div class="badge high">High</div>
                        <div class="meta" style="margin-top:6px">Open</div>
                    </div>
                </div>
                <div class="meta" style="margin-top:10px">Laptop shows BSOD intermittently.</div>
            </a>
        </section>

        <section class="card details">
            <h2>Ticket details</h2>
            <div class="meta" style="margin-bottom:8px">Select a ticket to view more details (static preview)</div>

            <div class="desc">
                <strong>Title:</strong>
                <div style="margin-top:6px;font-weight:600">Cannot connect to VPN • TCK-1001</div>
                <div class="meta" style="margin-top:6px">Created: 2026-03-01 09:15</div>
                <div style="margin-top:12px"><strong>Description</strong>
                    <p class="meta" style="margin-top:6px">User reports VPN fails with authentication error. Occurs on Windows 10 and macOS. Ticket is high priority because many remote users are blocked.</p>
                </div>
            </div>

            <div style="margin-top:12px">
                <label class="meta">Assign to</label>
                <div class="assign">
                    <select aria-label="assign">
                        <option>Alice Johnson — Network Tech (E-1)</option>
                        <option>Charlie Lee — Hardware Tech (E-3)</option>
                    </select>
                    <button class="btn">Assign</button>
                </div>
                <div class="meta" style="margin-top:8px">(This is a static demo — assignment is non-functional without JavaScript/backend)</div>
            </div>
        </section>

        <aside class="card employees">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
                <strong>Employees</strong>
                <span class="meta">Availability</span>
            </div>

            <div class="emp">
                <div>
                    <div style="font-weight:600">Alice Johnson</div>
                    <div class="meta">Network Tech • E-1</div>
                </div>
                <div class="status available">Available</div>
            </div>

            <div class="emp">
                <div>
                    <div style="font-weight:600">Bob Smith</div>
                    <div class="meta">Software Support • E-2</div>
                </div>
                <div class="status busy">Busy</div>
            </div>

            <div class="emp">
                <div>
                    <div style="font-weight:600">Charlie Lee</div>
                    <div class="meta">Hardware Tech • E-3</div>
                </div>
                <div class="status available">Available</div>
            </div>

        </aside>
    </main>

    <footer>Static HTML/CSS demo for admin dashboard — no JavaScript included.</footer>
</body>
</html>