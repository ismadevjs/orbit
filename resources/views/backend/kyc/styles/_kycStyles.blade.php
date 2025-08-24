@push('styles')
<style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #fafafa 0%, #ffffff 100%);
            color: #333;
            direction: rtl;
            text-align: right;
        }

        .upload-success {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #28a745;
            /* Green color */
            font-size: 1.2rem;
            font-weight: bold;
        }

        .upload-success i {
            font-size: 1.5rem;
        }


        .verification-wrapper {
            max-width: 700px;
            margin: 70px auto;
            position: relative;
            padding: 0 20px;
        }

        .header-section {
            margin-bottom: 40px;
        }

        .animated-title {
            background: linear-gradient(90deg, #0d6efd, #6610f2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: hue-rotate 5s infinite linear;
        }

        @keyframes hue-rotate {
            0% {
                filter: hue-rotate(0deg);
            }

            100% {
                filter: hue-rotate(360deg);
            }
        }

        .subtitle {
            color: #666;
        }

        .progress-container {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 60px;
        }

        .progress-container .circle {
            background: #ddd;
            border-radius: 50%;
            height: 20px;
            width: 20px;
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .progress-container .circle.active {
            background: #0d6efd;
        }

        .progress {
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            height: 3px;
            background: #0d6efd;
            width: 0;
            z-index: 1;
            transition: width 0.3s ease;
        }

        .steps-container {
            position: relative;
        }

        .step-card {
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 40px;
            opacity: 0;
            transform: translateY(20px);
        }

        .step-card.fade-in {
            animation: fadeInUp 0.5s forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .step-title {
            font-weight: 600;
            margin-bottom: 10px;
        }

        .step-instruction {
            color: #666;
            margin-bottom: 30px;
        }

        .form-control-custom {
            background: transparent;
            border: none;
            border-bottom: 1px solid #ccc;
            border-radius: 0;
            padding: 0.75rem 0;
            font-size: 1.1rem;
            box-shadow: none;
        }

        .form-control-custom:focus {
            border-color: #0d6efd;
            outline: none;
            box-shadow: none;
        }

        .floating-label {
            position: relative;
        }

        .floating-label label {
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            pointer-events: none;
            transition: 0.3s;
            color: #aaa;
            font-size: 1rem;
        }

        .form-control-custom:focus+label,
        .form-control-custom:not(:placeholder-shown)+label {
            top: 0%;
            transform: translateY(-100%);
            font-size: 0.8rem;
            color: #0d6efd;
        }

        .form-select-custom {
            background: transparent;
            border: none;
            border-bottom: 1px solid #ccc;
            border-radius: 0;
            padding: 0.75rem 0;
            appearance: none;
            font-size: 1.1rem;
            color: #333;
        }

        .form-select-custom:focus {
            outline: none;
            border-color: #0d6efd;
        }

        .form-select-custom+label {
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            pointer-events: none;
            transition: 0.3s;
            color: #aaa;
            font-size: 1rem;
        }

        .form-select-custom:focus+label,
        .form-select-custom:not([value=""])+label {
            top: 0%;
            transform: translateY(-100%);
            font-size: 0.8rem;
            color: #0d6efd;
        }

        .btn {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        .capture-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
        }

        .overlay-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(10px) brightness(0.7);
        }

        .capture-card {
            position: relative;
            background: #111;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            padding: 30px;
            width: 90%;
            max-width: 500px;
            text-align: center;
            color: #fff;
            z-index: 1000;
            animation: scaleIn 0.3s ease forwards;
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .close-overlay-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.2rem;
            cursor: pointer;
        }

        .close-overlay-btn:hover {
            color: #ccc;
        }

        .camera-container video {
            width: 100%;
            border-radius: 8px;
        }

        .canvas-preview {
            width: 100%;
            border: 1px solid #444;
            border-radius: 8px;
        }

        .request-camera-btn {
            border-color: #fff;
            color: #fff;
        }

        .request-camera-btn:hover {
            background: #0d6efd;
            border-color: #0d6efd;
        }

        .document-section {
            margin-bottom: 20px;
        }

        #id-front-preview img,
        #id-back-preview img,
        #passport-preview img,
        #license-front-preview img,
        #license-back-preview img,
        #selfie-preview img,
        #residency-preview img {
            /* Added residency-preview */
            width: 60px;
            height: auto;
            border-radius: 5px;
            margin-left: 10px;
            vertical-align: middle;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 576px) {
            .verification-wrapper {
                margin: 30px auto;
            }

            .display-4 {
                font-size: 1.8rem;
            }

            .form-control-custom,
            .form-select-custom {
                font-size: 1rem;
            }
        }
    </style>
@endpush