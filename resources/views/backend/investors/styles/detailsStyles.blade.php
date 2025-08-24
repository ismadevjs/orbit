@push('styles')
    <link href="
        https://cdn.jsdelivr.net/npm/filepond@4.32.7/dist/filepond.min.css
        " rel="stylesheet">

    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
    <link href="{{asset('lightbox.css')}}" rel="stylesheet">
    <style>
        .swal-rtl {
            direction: rtl !important;
            text-align: right !important;
            font-family: 'Arial', 'Tahoma', sans-serif !important;
        }

        .swal-title-rtl {
            font-size: 1.2rem !important;
            margin-bottom: 15px !important;
        }

        .swal-html-rtl {
            padding: 15px !important;
        }

        .swal2-input {
            direction: rtl !important;
            text-align: right !important;
            width: 100% !important;
            max-width: 300px !important;
            margin-bottom: 15px !important;
        }


        .doc-upload-container {
            width: 100%;
            max-width: 1200px;
            margin: 20px auto;
        }

        .doc-upload-block {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .doc-upload-block:hover {
            transform: translateY(-5px);
        }

        .doc-upload-content {
            padding: 30px;
        }

        .doc-upload-title {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
            font-weight: 600;
        }

        .doc-upload-form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }

        .doc-upload-col {
            flex: 1 1 300px;
            position: relative;
            margin-bottom: 25px;
        }

        .doc-upload-label {
            display: block;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #34495e;
            text-align: right;
        }

        .doc-submit-btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
            font-weight: 600;
        }

        .doc-submit-btn:hover {
            background-color: #2980b9;
        }

        .doc-submit-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        /* Custom FilePond Styling */
        .filepond--panel-root {
            background-color: rgba(52, 152, 219, 0.05) !important;
            border: 2px dashed #3498db !important;
        }

        .filepond--drop-label {
            color: #7f8c8d !important;
            font-size: 14px !important;
        }

        .filepond--label-action {
            text-decoration-color: #3498db !important;
            color: #3498db !important;
            font-weight: 600 !important;
        }

        .filepond--root {
            margin-bottom: 0 !important;
        }

        .filepond--root .filepond--drop-label {
            min-height: 6em !important;
        }

        .filepond--credits {
            display: none !important;
        }

        .filepond--item {
            padding: 0 !important;
        }

        /* Animation for FilePond borders */
        @keyframes pulse-border {
            0% {
                border-color: #3498db !important;
            }

            50% {
                border-color: #2980b9 !important;
            }

            100% {
                border-color: #3498db !important;
            }
        }

        .filepond--panel-root:hover {
            animation: pulse-border 2s infinite;
        }

        /* Make FilePond responsive */
        .filepond--root {
            width: 100% !important;
            min-height: 180px !important;
        }

        /* Style for the upload button */
        .filepond--file-action-button {
            background-color: rgba(52, 152, 219, 0.8) !important;
        }

        .filepond--file-action-button:hover {
            background-color: rgb(52, 152, 219) !important;
        }

        /* Fix RTL issues with FilePond */
        .filepond--file-info {
            margin-right: 0.5em !important;
        }

        .txm-wrapper {
            --txm-primary: #4f46e5;
            --txm-success: #16a34a;
            --txm-danger: #dc2626;
            --txm-text-dark: #1e293b;
            --txm-text-light: #64748b;
            --txm-bg-hover: #f8fafc;
            --txm-border-light: #e2e8f0;
        }

        .txm-container {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03),
                0 2px 10px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            position: relative;
        }

        .txm-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--txm-primary), #818cf8);
        }

        .txm-header {
            padding: 2rem;
            background: linear-gradient(to bottom, rgba(79, 70, 229, 0.02), transparent);
            border-bottom: 1px solid var(--txm-border-light);
        }

        .txm-header__title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--txm-primary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .txm-header__title::before {
            content: '●';
            color: var(--txm-primary);
            font-size: 0.75rem;
        }

        .txm-header__subtitle {
            color: var(--txm-text-light);
            font-size: 1rem;
            font-weight: 400;
        }

        .txm-content {
            padding: 1.5rem;
        }

        .txm-transaction {
            background: var(--txm-bg-hover);
            border-radius: 16px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border: 1px solid var(--txm-border-light);
        }

        .txm-transaction:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        }

        .txm-transaction__header {
            padding: 1.5rem;
            border-bottom: 1px dashed var(--txm-border-light);
        }

        .txm-transaction__id {
            font-size: 0.9rem;
            color: var(--txm-text-light);
            font-weight: 500;
        }

        .txm-transaction__amount {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--txm-text-dark);
            font-family: 'Arial', sans-serif;
        }

        .txm-transaction__actions {
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        .txm-btn {
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .txm-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .txm-btn:hover::before {
            transform: translateX(0);
        }

        .txm-btn--accept {
            background: var(--txm-success);
        }

        .txm-btn--accept:hover {
            background: #15803d;
        }

        .txm-btn--decline {
            background: var(--txm-danger);
        }

        .txm-btn--decline:hover {
            background: #b91c1c;
        }

        .txm-empty {
            text-align: center;
            padding: 3rem 1rem;
        }

        .txm-empty__icon {
            font-size: 2.5rem;
            color: var(--txm-text-light);
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .txm-empty__text {
            color: var(--txm-text-light);
            font-size: 1.1rem;
        }

        .txm-transaction--withdrawal {
            background: #fef3c7;
            /* Soft warning color */
            border-left: 5px solid #facc15;
            /* Gold accent */
        }


        @keyframes txm-pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Enhanced Timeline Styles with Color Gradients and Improved Animations */
        .etl-container {
            padding: 50px 20px;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }

        .etl-header {
            text-align: center;
            margin-bottom: 60px;
            font-size: 32px;
            color: #343a40;
            font-weight: 700;
            position: relative;
        }

        .etl-header::after {
            content: '';
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #0d6efd, #6610f2);
            display: block;
            margin: 15px auto 0;
            border-radius: 2px;
        }

        .etl-timeline {
            display: flex;
            justify-content: space-between;
            position: relative;
            padding: 20px 0;
        }

        .etl-timeline::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 5%;
            width: 90%;
            height: 4px;
            background: linear-gradient(90deg, #0d6efd, #6610f2);
            z-index: 1;
            border-radius: 2px;
        }

        .etl-step {
            position: relative;
            text-align: center;
            width: 22%;
            z-index: 2;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .etl-step:hover {
            transform: translateY(-15px);
        }

        .etl-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 20px;
            /* Increased bottom margin to move text down */
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background 0.3s ease, transform 0.3s ease;
            cursor: pointer;
        }

        .etl-completed .etl-icon {
            background: linear-gradient(135deg, #28a745, #218838);
            /* Success Gradient */
            transform: scale(1.05);
        }

        .etl-pending .etl-icon {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            /* Warning Gradient */
        }

        .etl-title {
            font-size: 22px;
            margin-bottom: 10px;
            color: #495057;
            font-weight: 600;
            transition: color 0.3s ease;
            margin-top: 57px;
        }

        .etl-status {
            font-size: 16px;
            color: #6c757d;
            transition: color 0.3s ease;
        }

        .etl-completed .etl-title,
        .etl-completed .etl-status {
            color: #155724;
            /* Dark Green for Success */
        }

        .etl-pending .etl-title,
        .etl-pending .etl-status {
            color: #856404;
            /* Dark Yellow for Pending */
        }

        /* Step Indicator Dots */
        .etl-step::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 16px;
            height: 16px;
            background-color: #fff;
            border: 4px solid #0d6efd;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .etl-completed::after {
            background-color: #28a745;
            /* Success Color */
            border-color: #28a745;
        }

        .etl-pending::after {
            background-color: #ffc107;
            /* Warning Color */
            border-color: #ffc107;
        }

        /* Animations */
        .etl-step {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.6s forwards;
        }

        .etl-step:nth-child(1) {
            animation-delay: 0.3s;
        }

        .etl-step:nth-child(2) {
            animation-delay: 0.5s;
        }

        .etl-step:nth-child(3) {
            animation-delay: 0.7s;
        }

        .etl-step:nth-child(4) {
            animation-delay: 0.9s;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Beat Animation for Icons on Hover */
        @keyframes beat {

            0%,
            100% {
                transform: scale(1);
            }

            25% {
                transform: scale(1.1);
            }

            50% {
                transform: scale(1);
            }

            75% {
                transform: scale(1.05);
            }
        }

        .etl-icon:hover {
            animation: beat 0.6s infinite;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .etl-timeline {
                flex-wrap: wrap;
                justify-content: center;
            }

            .etl-step {
                width: 45%;
                margin-bottom: 50px;
            }

            .etl-timeline::before {
                left: 50%;
                top: 20px;
                width: 2px;
                height: auto;
                background: linear-gradient(180deg, #0d6efd, #6610f2);
            }

            .etl-step::after {
                top: 20px;
                left: 50%;
                transform: translate(-50%, -50%);
            }
        }

        @media (max-width: 576px) {
            .etl-timeline::before {
                left: 20px;
                top: 0;
                width: 2px;
                height: 100%;
                background: linear-gradient(180deg, #0d6efd, #6610f2);
            }

            .etl-step {
                width: 100%;
                text-align: left;
                padding-left: 50px;
                margin-bottom: 60px;
            }

            .etl-step::after {
                top: 30px;
                left: 20px;
                transform: translate(-50%, -50%);
            }

            .etl-icon {
                margin: 0 0 20px 0;
            }

            .etl-content {
                text-align: left;
            }
        }

        /* Enhanced Table Styling */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.1);
            /* Subtle hover effect */
            transition: background-color 0.3s ease-in-out;
        }

        /* Gradient Header */
        .bg-gradient-primary {
            background: linear-gradient(90deg, #007bff, #0056b3);
            color: #fff;
        }

        /* Badge Animations */
        .badge {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.25rem;
            display: inline-block;
            transition: all 0.3s ease;
        }

        /* Badge Hover Effects */
        .badge:hover {
            transform: scale(1.1);
            box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.15);
        }
    </style>


    <style>
        .plan-card {
            position: relative;
            width: 300px;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            color: #ffffff;
            overflow: hidden;
            margin: 20px auto;
            background: #000;
            isolation: isolate;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .plan-card:hover {
            transform: translateY(-10px) scale(1.02);
        }

        /* BRONZE PLAN */
        .bronze-plan {
            background: #1a1a1a;
        }

        .bronze-plan .animation-container {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg, rgba(205, 127, 50, 0.1) 1px, transparent 1px) 0 0 / 20px 20px,
                linear-gradient(0deg, rgba(205, 127, 50, 0.1) 1px, transparent 1px) 0 0 / 20px 20px;
        }

        .bronze-plan .animation-container::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 50% 50%,
                    rgba(205, 127, 50, 0.8),
                    rgba(139, 69, 19, 0.4),
                    transparent 70%);
            animation: bronzePulse 4s ease-in-out infinite;
            filter: blur(20px);
        }

        .bronze-plan .animation-container::after {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            top: -25%;
            left: -25%;
            background: conic-gradient(from 0deg,
                    transparent 0deg,
                    rgba(205, 127, 50, 0.2) 90deg,
                    transparent 180deg);
            animation: bronzeRotate 8s linear infinite;
        }

        @keyframes bronzePulse {

            0%,
            100% {
                opacity: 0.5;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                transform: scale(1.2);
            }
        }

        @keyframes bronzeRotate {
            to {
                transform: rotate(360deg);
            }
        }

        /* GOLD PLAN */
        .gold-plan {
            background: #1a1a1a;
        }

        .gold-plan .animation-container {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg, rgba(212, 180, 4, 0.74) 1px, transparent 1px) 0 0 / 20px 20px,
                linear-gradient(0deg, rgba(244, 208, 4, 0.609) 1px, transparent 1px) 0 0 / 20px 20px;
        }

        .gold-plan .animation-container::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 50% 50%,
                    rgba(255, 215, 0, 0.8),
                    rgba(184, 134, 11, 0.4),
                    transparent 70%);
            animation: bronzePulse 4s ease-in-out infinite;
            filter: blur(20px);
        }

        .gold-plan .animation-container::after {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            top: -25%;
            left: -25%;
            background: conic-gradient(from 0deg,
                    transparent 0deg,
                    rgba(255, 215, 0, 0.2) 90deg,
                    transparent 180deg);
            animation: bronzeRotate 8s linear infinite;
        }

        /* CRYSTAL PLAN */
        .crystal-plan {
            background: #1a1a1a;
        }

        .crystal-plan .animation-container {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg, rgba(173, 216, 230, 0.1) 1px, transparent 1px) 0 0 / 20px 20px,
                linear-gradient(0deg, rgba(173, 216, 230, 0.1) 1px, transparent 1px) 0 0 / 20px 20px;
        }

        .crystal-plan .animation-container::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 50% 50%,
                    rgba(173, 216, 230, 0.8),
                    rgba(70, 130, 180, 0.4),
                    transparent 70%);
            animation: bronzePulse 4s ease-in-out infinite;
            filter: blur(20px);
        }

        .crystal-plan .animation-container::after {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            top: -25%;
            left: -25%;
            background: conic-gradient(from 0deg,
                    transparent 0deg,
                    rgba(173, 216, 230, 0.2) 90deg,
                    transparent 180deg);
            animation: bronzeRotate 8s linear infinite;
        }

        /* Enhanced content styling */
        .plan-content {
            position: relative;
            z-index: 2;
            padding: 20px;
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .plan-title {
            font-size: 1.7rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
            letter-spacing: 1px;
        }

        .plan-level {
            font-size: 1.4rem;
            font-weight: bold;
            margin: 15px 0;
            background: linear-gradient(to right, #fff, #ccc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .plan-description {
            font-size: 1rem;
            line-height: 1.5;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        /* Modern hover effects */
        .plan-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 15px;
            padding: 2px;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .plan-card:hover::after {
            opacity: 1;
        }
    </style>
@endpush