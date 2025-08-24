<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شكرًا لتواصلك مع أموال فلو</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            color: #2d3748;
        }
        
        .container {
            max-width: 800px;
            background-color: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }
        
        .logo {
            margin-bottom: 20px;
            animation: fadeIn 1s ease-out forwards;
        }
        
        h1 {
            color: #3498db;
            margin-bottom: 20px;
            font-size: 2.2rem;
            animation: slideInRight 0.7s ease-out forwards;
            opacity: 0;
        }
        
        p {
            font-size: 1.2rem;
            line-height: 1.8;
            margin-bottom: 25px;
            animation: fadeIn 1s ease-out 0.5s forwards;
            opacity: 0;
        }
        
        .success-checkmark {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
        }
        
        .check-icon {
            width: 80px;
            height: 80px;
            position: relative;
            border-radius: 50%;
            box-sizing: content-box;
            border: 4px solid #4CAF50;
            margin: 0 auto;
        }
        
        .check-icon::before {
            top: 3px;
            left: -2px;
            width: 30px;
            transform-origin: 100% 50%;
            border-radius: 100px 0 0 100px;
        }
        
        .check-icon::after {
            top: 0;
            left: 30px;
            width: 60px;
            transform-origin: 0 50%;
            border-radius: 0 100px 100px 0;
            animation: rotate-circle 4.25s ease-in;
        }
        
        .check-icon::before, .check-icon::after {
            content: '';
            height: 100px;
            position: absolute;
            background: #FFFFFF;
            transform: rotate(-45deg);
        }
        
        .check-icon .icon-line {
            height: 5px;
            background-color: #4CAF50;
            display: block;
            border-radius: 2px;
            position: absolute;
            z-index: 10;
        }
        
        .check-icon .icon-line.line-tip {
            top: 46px;
            left: 14px;
            width: 25px;
            transform: rotate(45deg);
            animation: icon-line-tip 0.75s;
        }
        
        .check-icon .icon-line.line-long {
            top: 38px;
            right: 8px;
            width: 47px;
            transform: rotate(-45deg);
            animation: icon-line-long 0.75s;
        }
        
        .check-icon .icon-circle {
            top: -4px;
            left: -4px;
            z-index: 10;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            position: absolute;
            box-sizing: content-box;
            border: 4px solid rgba(76, 175, 80, 0.5);
        }
        
        .check-icon .icon-fix {
            top: 8px;
            width: 5px;
            left: 26px;
            z-index: 1;
            height: 85px;
            position: absolute;
            transform: rotate(-45deg);
            background-color: #FFFFFF;
        }
        
        @keyframes rotate-circle {
            0% { transform: rotate(-45deg); }
            5% { transform: rotate(-45deg); }
            12% { transform: rotate(-405deg); }
            100% { transform: rotate(-405deg); }
        }
        
        @keyframes icon-line-tip {
            0% { width: 0; left: 1px; top: 19px; }
            54% { width: 0; left: 1px; top: 19px; }
            70% { width: 50px; left: -8px; top: 37px; }
            84% { width: 17px; left: 21px; top: 48px; }
            100% { width: 25px; left: 14px; top: 46px; }
        }
        
        @keyframes icon-line-long {
            0% { width: 0; right: 46px; top: 54px; }
            65% { width: 0; right: 46px; top: 54px; }
            84% { width: 55px; right: 0px; top: 35px; }
            100% { width: 47px; right: 8px; top: 38px; }
        }
        
        .particle {
            position: absolute;
            width: 10px;
            height: 10px;
            background: linear-gradient(135deg, #3498db, #1abc9c);
            border-radius: 50%;
            animation: particle 2s infinite linear;
            opacity: 0;
        }
        
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        
        @keyframes slideInRight {
            0% { transform: translateX(50px); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes particle {
            0% { transform: translate(0, 0) rotate(0deg); opacity: 0; }
            20% { opacity: 1; }
            100% { transform: translate(var(--tx), var(--ty)) rotate(360deg); opacity: 0; }
        }
        
        .floating-coin {
            position: absolute;
            z-index: -1;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
            100% { transform: translateY(0) rotate(0deg); }
        }
        
        .contact-info {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            animation: fadeIn 1s ease-out 1s forwards;
            opacity: 0;
        }
        
        .icon {
            margin: 0 10px;
            color: #3498db;
            font-size: 1.5rem;
        }
        
        .return-button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
            animation: fadeIn 1s ease-out 1.2s forwards;
            opacity: 0;
        }
        
        .return-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <div>
                <img src="{{ asset('storage/'.getSettingValue('logo')) }}" width="200" alt="Logo">
            </div>
        </div>
        
        <h1>شكرًا لتواصلك مع "أموال فلو"</h1>
        
        <div class="success-checkmark">
            <div class="check-icon">
                <span class="icon-line line-tip"></span>
                <span class="icon-line line-long"></span>
                <div class="icon-circle"></div>
                <div class="icon-fix"></div>
            </div>
        </div>
        
        <p>نحن نقدر اهتمامك ووقتك في تعبئة النموذج. تم استلام طلبك بنجاح، ونحن الآن نعمل على معالجة بياناتك بأسرع وقت ممكن.</p>
        
        <p>سنقوم بالتواصل معك قريبًا عبر البريد الإلكتروني الذي قدمته لتزويدك بكافة التفاصيل والإجراءات التالية. إذا كان لديك أي استفسار أو تحتاج إلى مساعدة إضافية، لا تتردد في الاتصال بنا</p>
        
        <div class="contact-info">
            <span class="icon">📧</span> info@amwalflow.com
            <span class="icon">☎️</span> +966 123 456 789
        </div>
        
        <a href="/" class="return-button">العودة للموقع الرئيسي</a>
        
        <svg class="floating-coin" style="top: 20%; left: 10%; width: 60px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM256 464c-114.7 0-208-93.31-208-208S141.3 48 256 48s208 93.31 208 208S370.7 464 256 464zM296 336h-16V248C280 234.8 269.3 224 256 224H224C210.8 224 200 234.8 200 248S210.8 272 224 272h8v64h-16C202.8 336 192 346.8 192 360S202.8 384 216 384h80c13.25 0 24-10.75 24-24S309.3 336 296 336zM256 192c17.67 0 32-14.33 32-32c0-17.67-14.33-32-32-32S224 142.3 224 160C224 177.7 238.3 192 256 192z" fill="#3498db"/>
        </svg>
        
        <svg class="floating-coin" style="top: 70%; right: 10%; width: 80px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM256 464c-114.7 0-208-93.31-208-208S141.3 48 256 48s208 93.31 208 208S370.7 464 256 464zM296 336h-16V248C280 234.8 269.3 224 256 224H224C210.8 224 200 234.8 200 248S210.8 272 224 272h8v64h-16C202.8 336 192 346.8 192 360S202.8 384 216 384h80c13.25 0 24-10.75 24-24S309.3 336 296 336zM256 192c17.67 0 32-14.33 32-32c0-17.67-14.33-32-32-32S224 142.3 224 160C224 177.7 238.3 192 256 192z" fill="#1abc9c"/>
        </svg>
    </div>
    
    <script>
        function createParticles() {
            const container = document.querySelector('.container');
            for (let i = 0; i < 15; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                const startX = Math.random() * 100;
                const startY = Math.random() * 100;
                
                const tx = (Math.random() - 0.5) * 200;
                const ty = (Math.random() - 0.5) * 200;
                
                const size = Math.random() * 8 + 5;
                
                const delay = Math.random() * 2;
                
                particle.style.setProperty('--tx', `${tx}px`);
                particle.style.setProperty('--ty', `${ty}px`);
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${startX}%`;
                particle.style.top = `${startY}%`;
                particle.style.animationDelay = `${delay}s`;
                
                container.appendChild(particle);
            }
        }
        
        window.onload = function() {
            createParticles();
            setInterval(createParticles, 3000);
        };
    </script>
</body>
</html>