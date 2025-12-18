<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Management Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fb;
            color: #333;
            padding: 20px;
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e1e5eb;
        }
        
        .dashboard-header h1 {
            color: #2c3e50;
            font-size: 28px;
            font-weight: 700;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #5a6c7d;
            font-weight: 500;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3498db, #2c3e50);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 5px solid #3498db;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }
        
        .stat-card.today {
            border-left-color: #2ecc71;
        }
        
        .stat-card.monthly {
            border-left-color: #e74c3c;
        }
        
        .stat-card.pending {
            border-left-color: #f39c12;
        }
        
        .stat-title {
            font-size: 14px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .stat-note {
            font-size: 14px;
            color: #95a5a6;
        }
        
        .no-pending {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #27ae60;
            background-color: rgba(39, 174, 96, 0.1);
            padding: 8px 15px;
            border-radius: 50px;
            width: fit-content;
            margin-top: 10px;
        }
        
        .actions-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .action-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }
        
        .action-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 28px;
            color: white;
        }
        
        .create-bill .action-icon {
            background: linear-gradient(135deg, #3498db, #2980b9);
        }
        
        .payment-history .action-icon {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
        }
        
        .financial-reports .action-icon {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
        }
        
        .action-card h3 {
            font-size: 20px;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .action-card p {
            color: #7f8c8d;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        
        .action-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .action-btn:hover {
            background: #2980b9;
            transform: scale(1.05);
        }
        
        .financial-reports .action-btn {
            background: #9b59b6;
        }
        
        .financial-reports .action-btn:hover {
            background: #8e44ad;
        }
        
        .payment-history .action-btn {
            background: #2ecc71;
        }
        
        .payment-history .action-btn:hover {
            background: #27ae60;
        }
        
        @media (max-width: 768px) {
            .stats-container, .actions-container {
                grid-template-columns: 1fr;
            }
            
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1><i class="fas fa-file-invoice-dollar"></i> Billing Management</h1>
            <div class="user-profile">
                <div class="user-avatar">AM</div>
                <span>Admin Manager</span>
            </div>
        </div>
        
        <div class="stats-container">
            <div class="stat-card today">
                <div class="stat-title">
                    <i class="fas fa-sun"></i> Today's Revenue
                </div>
                <div class="stat-value">ETB 0.00</div>
                <div class="stat-note">No transactions recorded today</div>
            </div>
            
            <div class="stat-card monthly">
                <div class="stat-title">
                    <i class="fas fa-calendar-alt"></i> Monthly Revenue
                </div>
                <div class="stat-value">ETB 0.00</div>
                <div class="stat-note">Revenue for current month</div>
            </div>
            
            <div class="stat-card pending">
                <div class="stat-title">
                    <i class="fas fa-clock"></i> Pending Bills
                </div>
                <div class="stat-value">0</div>
                <div class="no-pending">
                    <i class="fas fa-check-circle"></i> No pending bills
                </div>
            </div>
        </div>
        
        <div class="actions-container">
            <div class="action-card create-bill">
                <div class="action-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <h3>Create New Bill</h3>
                <p>Generate invoice for patient with detailed billing information and itemized charges.</p>
                <button class="action-btn">
                    <i class="fas fa-file-invoice"></i> Create Bill
                </button>
            </div>
            
            <div class="action-card payment-history">
                <div class="action-icon">
                    <i class="fas fa-history"></i>
                </div>
                <h3>Payment History</h3>
                <p>View all transactions, filter by date range, patient, or payment method for complete financial tracking.</p>
                <button class="action-btn">
                    <i class="fas fa-eye"></i> View All Transactions
                </button>
            </div>
            
            <div class="action-card financial-reports">
                <div class="action-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3>Financial Reports</h3>
                <p>Generate detailed financial reports, export data, and visualize revenue trends with charts and graphs.</p>
                <button class="action-btn">
                    <i class="fas fa-chart-line"></i> Generate Reports
                </button>
            </div>
        </div>
    </div>

    <script>
        // Add interactivity to buttons
        document.querySelectorAll('.action-btn').forEach(button => {
            button.addEventListener('click', function() {
                const action = this.closest('.action-card').querySelector('h3').textContent;
                alert(`In a real application, this would open the ${action} module.`);
            });
        });
        
        // Simulate real-time revenue updates (for demo purposes)
        setInterval(() => {
            const todayElement = document.querySelector('.today .stat-value');
            const monthlyElement = document.querySelector('.monthly .stat-value');
            
            // Randomly update values for demo effect
            if (Math.random() > 0.7) {
                const randomIncrement = (Math.random() * 100).toFixed(2);
                const todayValue = parseFloat(todayElement.textContent.replace('ETB ', ''));
                const monthlyValue = parseFloat(monthlyElement.textContent.replace('ETB ', ''));
                
                todayElement.textContent = `ETB ${(todayValue + parseFloat(randomIncrement)).toFixed(2)}`;
                monthlyElement.textContent = `ETB ${(monthlyValue + parseFloat(randomIncrement)).toFixed(2)}`;
            }
        }, 5000);
    </script>
</body>
</html>