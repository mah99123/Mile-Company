<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إعادة تعيين كلمة المرور - نظام إدارة المنصات المتعددة</title>
    
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
            --warning-color: #f39c12;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --info-color: #3498db;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, var(--warning-color) 0%, var(--primary-color) 100%);
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
            background: linear-gradient(45deg, var(--warning-color), var(--primary-color), var(--accent-gold), var(--secondary-color));
            background-size: 400% 400%;
            animation: gradientShift 18s ease infinite;
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
            animation: float 12s ease-in-out infinite;
        }

        .particle:nth-child(1) { width: 80px; height: 80px; top: 15%; left: 15%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 60px; height: 60px; top: 75%; left: 85%; animation-delay: 4s; }
        .particle:nth-child(3) { width: 70px; height: 70px; top: 85%; left: 25%; animation-delay: 8s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-35px) rotate(180deg); }
        }

        /* حاوي إعادة تعيين كلمة المرور */
        .reset-container {
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
            background: linear-gradient(135deg, var(--warning-color), var(--accent-gold));
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 10px 30px rgba(243, 156, 18, 0.3);
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
            background: linear-gradient(135deg, var(--warning-color), var(--accent-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .system-subtitle {
            color: #718096;
            font-size: 1rem;
            margin-bottom: 2rem;
        }

        /* عنوان إعادة تعيين كلمة المرور */
        .reset-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 0.5rem;
            animation: fadeInDown 0.8s ease-out 0.5s both;
        }

        .reset-subtitle {
            color: #718096;
            text-align: center;
            margin-bottom: 2rem;
            animation: fadeInDown 0.8s ease-out 0.7s both;
        }

        /* حقول الإدخال */
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
            animation: fadeInLeft 0.8s ease-out both;
        }

        .form-group:nth-child(1) { animation-delay: 0.9s; }
        .form-group:nth-child(2) { animation-delay: 1.1s; }
        .form-group:nth-child(3) { animation-delay: 1.3s; }

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
            border-color: var(--warning-color);
            box-shadow: 0 0 0 3px rgba(243, 156, 18, 0.1);
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
            color: var(--warning-color);
        }

        /* زر إعادة التعيين */
        .reset-button {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--warning-color), var(--accent-gold));
            color: white;
            border: none;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out 1.5s both;
        }

        .reset-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(243, 156, 18, 0.4);
        }

        .reset-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .reset-button:hover::before {
            left: 100%;
        }

        /* رسائل الخطأ */
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

        .reset-button.loading .loading {
            display: inline-block;
        }

        /* استجابة للأجهزة المحمولة */
        @media (max-width: 768px) {
            .reset-container {
                margin: 1rem;
                padding: 2rem;
            }
            
            .system-title {
                font-size: 1.5rem;
            }
            
            .reset-title {
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

    <!-- حاوي إعادة تعيين كلمة المرور -->
    <div class="reset-container">
        <!-- شعار الشركة -->
        <div class="logo-container">
            <div class="logo">
                <i class="fas fa-lock-open"></i>
            </div>
            <div class="system-title">محمد فون تك</div>
            <div class="system-subtitle">نظام إدارة المنصات المتعددة</div>
        </div>

        <!-- عنوان إعادة تعيين كلمة المرور -->
        <h1 class="reset-title">إعادة تعيين كلمة المرور</h1>
        <p class="reset-subtitle">أدخل كلمة المرور الجديدة</p>

        <!-- نموذج إعادة تعيين كلمة المرور -->
        <form method="POST" action="{{ route('password.store') }}" id="resetForm">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                        value="{{ old('email', $request->email) }}" 
                        required 
                        autofocus 
                        autocomplete="username"
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

            <!-- كلمة المرور -->
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i>
                    كلمة المرور الجديدة
                </label>
                <div style="position: relative;">
                    <input 
                        id="password" 
                        class="form-input" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="new-password"
                        placeholder="أدخل كلمة المرور الجديدة"
                    >
                    <i class="fas fa-lock input-icon"></i>
                    <i class="fas fa-eye" id="togglePassword" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); cursor: pointer; color: #a0aec0;"></i>
                </div>
                @error('password')
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- تأكيد كلمة المرور -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">
                    <i class="fas fa-lock"></i>
                    تأكيد كلمة المرور
                </label>
                <div style="position: relative;">
                    <input 
                        id="password_confirmation" 
                        class="form-input" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        placeholder="أعد إدخال كلمة المرور"
                    >
                    <i class="fas fa-lock input-icon"></i>
                    <i class="fas fa-eye" id="togglePasswordConfirm" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); cursor: pointer; color: #a0aec0;"></i>
                </div>
                @error('password_confirmation')
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- زر إعادة التعيين -->
            <button type="submit" class="reset-button" id="resetBtn">
                <i class="fas fa-key"></i>
                إعادة تعيين كلمة المرور
                <div class="loading"></div>
            </button>
        </form>
    </div>

    <script>
        // تبديل إظهار/إخفاء كلمة المرور
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // تبديل إظهار/إخفاء تأكيد كلمة المرور
        document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
            const password = document.getElementById('password_confirmation');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // تأثير التحميل عند الإرسال
        document.getElementById('resetForm').addEventListener('submit', function() {
            const btn = document.getElementById('resetBtn');
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

        // التحقق من تطابق كلمات المرور
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && password !== confirmPassword) {
                this.style.borderColor = 'var(--danger-color)';
            } else {
                this.style.borderColor = '#e2e8f0';
            }
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
