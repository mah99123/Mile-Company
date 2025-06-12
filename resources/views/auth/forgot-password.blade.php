<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>استعادة كلمة المرور - نظام إدارة المنصات المتعددة</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-gold: #f1c40f;
            --info-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, var(--info-color) 0%, var(--primary-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* خلفية متحركة */
        .animated-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, var(--info-color), var(--primary-color), var(--accent-gold), var(--secondary-color));
            background-size: 400% 400%;
            animation: gradientShift 20s ease infinite;
            z-index: -2;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* جزيئات متحركة */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 10s ease-in-out infinite;
        }

        .particle:nth-child(1) { width: 70px; height: 70px; top: 20%; left: 20%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 50px; height: 50px; top: 70%; left: 80%; animation-delay: 3s; }
        .particle:nth-child(3) { width: 60px; height: 60px; top: 80%; left: 30%; animation-delay: 6s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(180deg); }
        }

        /* حاوي استعادة كلمة المرور */
        .forgot-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 3rem;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideInUp 0.8s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* شعار الشركة */
        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
            animation: bounceIn 1s ease-out 0.3s both;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--info-color), var(--accent-gold));
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 10px 30px rgba(52, 152, 219, 0.3);
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .logo i {
            font-size: 2rem;
            color: white;
        }

        /* عنوان النظام */
        .system-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--info-color), var(--accent-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .system-subtitle {
            color: #718096;
            font-size: 1rem;
            margin-bottom: 2rem;
        }

        /* عنوان استعادة كلمة المرور */
        .forgot-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 0.5rem;
            animation: fadeInDown 0.8s ease-out 0.5s both;
        }

        .forgot-subtitle {
            color: #718096;
            text-align: center;
            margin-bottom: 2rem;
            animation: fadeInDown 0.8s ease-out 0.7s both;
            line-height: 1.5;
        }

        /* حقول الإدخال */
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
            animation: fadeInLeft 0.8s ease-out 0.9s both;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--info-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            transform: translateY(-2px);
        }

        .input-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .form-input:focus + .input-icon {
            color: var(--info-color);
        }

        /* زر الإرسال */
        .send-button {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--info-color), var(--accent-gold));
            color: white;
            border: none;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out 1.1s both;
        }

        .send-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(52, 152, 219, 0.4);
        }

        .send-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .send-button:hover::before {
            left: 100%;
        }

        /* رابط العودة */
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
            animation: fadeInUp 0.8s ease-out 1.3s both;
        }

        .back-link a {
            color: var(--info-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        /* رسائل النجاح والخطأ */
        .success-message {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border-right: 4px solid var(--success-color);
            animation: slideInRight 0.5s ease-out;
        }

        .error-message {
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: shakeX 0.5s ease-out;
        }

        /* تأثير التحميل */
        .loading {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .send-button.loading .loading {
            display: inline-block;
        }

        /* استجابة للأجهزة المحمولة */
        @media (max-width: 768px) {
            .forgot-container {
                margin: 1rem;
                padding: 2rem;
            }
            
            .system-title {
                font-size: 1.5rem;
            }
            
            .forgot-title {
                font-size: 1.7rem;
            }
        }

        /* تأثيرات الأنيميشن */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes shakeX {
            0%, 100% {
                transform: translateX(0);
            }
            10%, 30%, 50%, 70%, 90% {
                transform: translateX(-10px);
            }
            20%, 40%, 60%, 80% {
                transform: translateX(10px);
            }
        }
    </style>
</head>
<body>
    <!-- خلفية متحركة -->
    <div class="animated-bg"></div>
    
    <!-- جزيئات متحركة -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- حاوي استعادة كلمة المرور -->
    <div class="forgot-container">
        <!-- شعار الشركة -->
        <div class="logo-container">
            <div class="logo">
                <i class="fas fa-key"></i>
            </div>
            <div class="system-title">محمد فون تك</div>
            <div class="system-subtitle">نظام إدارة المنصات المتعددة</div>
        </div>

        <!-- عنوان استعادة كلمة المرور -->
        <h1 class="forgot-title">استعادة كلمة المرور</h1>
        <p class="forgot-subtitle">أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة تعيين كلمة المرور</p>

        <!-- رسالة النجاح -->
        @if (session('status'))
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                {{ session('status') }}
            </div>
        @endif

        <!-- نموذج استعادة كلمة المرور -->
        <form method="POST" action="{{ route('password.email') }}" id="forgotForm">
            @csrf

            <!-- البريد الإلكتروني -->
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope"></i>
                    البريد الإلكتروني
                </label>
                <div style="position: relative;">
                    <input 
                        id="email" 
                        class="form-input" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                        placeholder="أدخل بريدك الإلكتروني"
                    >
                    <i class="fas fa-envelope input-icon"></i>
                </div>
                @error('email')
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- زر الإرسال -->
            <button type="submit" class="send-button" id="sendBtn">
                <i class="fas fa-paper-plane"></i>
                إرسال رابط إعادة التعيين
                <div class="loading"></div>
            </button>
        </form>

        <!-- رابط العودة -->
        <div class="back-link">
            <a href="{{ route('login') }}">
                <i class="fas fa-arrow-right me-1"></i>
                العودة لتسجيل الدخول
            </a>
        </div>
    </div>

    <script>
        // تأثير التحميل عند الإرسال
        document.getElementById('forgotForm').addEventListener('submit', function() {
            const btn = document.getElementById('sendBtn');
            btn.classList.add('loading');
            btn.disabled = true;
        });

        // تأثيرات إضافية للحقول
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // تأثير الجزيئات التفاعلية
        document.addEventListener('mousemove', function(e) {
            const particles = document.querySelectorAll('.particle');
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            
            particles.forEach((particle, index) => {
                const speed = (index + 1) * 0.5;
                particle.style.transform = `translate(${x * speed * 10}px, ${y * speed * 10}px)`;
            });
        });
    </script>
</body>
</html>
