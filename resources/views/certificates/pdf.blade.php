<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        @page { size: A4 landscape; margin: 0; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background: #FAF8F5;
            width: 100%;
            height: 100%;
        }
        .certificate {
            width: 100%;
            min-height: 100vh;
            padding: 40px;
            position: relative;
        }
        .border-frame {
            border: 3px solid #3D2B1F;
            border-radius: 12px;
            padding: 50px 60px;
            min-height: calc(100vh - 80px);
            position: relative;
            background: white;
        }
        .inner-border {
            border: 1px solid #E8D5CC;
            border-radius: 8px;
            padding: 40px 50px;
            min-height: calc(100vh - 186px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .logo-text {
            font-size: 28px;
            font-weight: bold;
            color: #3D2B1F;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        .logo-text span {
            font-weight: normal;
        }
        .divider {
            width: 80px;
            height: 2px;
            background: #DC2626;
            margin: 20px auto;
        }
        .heading {
            font-size: 36px;
            font-weight: bold;
            color: #3D2B1F;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        .subheading {
            font-size: 14px;
            color: #6B6B6B;
            margin-bottom: 30px;
        }
        .student-name {
            font-size: 32px;
            font-weight: bold;
            color: #1A1A1A;
            margin-bottom: 15px;
            border-bottom: 2px solid #E8D5CC;
            padding-bottom: 10px;
            display: inline-block;
            min-width: 300px;
        }
        .completion-text {
            font-size: 14px;
            color: #6B6B6B;
            margin-bottom: 8px;
        }
        .course-title {
            font-size: 22px;
            font-weight: bold;
            color: #3D2B1F;
            margin-bottom: 30px;
        }
        .date {
            font-size: 13px;
            color: #6B6B6B;
            margin-bottom: 5px;
        }
        .cert-number {
            font-size: 11px;
            color: #999;
            margin-bottom: 15px;
        }
        .verify-url {
            font-size: 10px;
            color: #999;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border-frame">
            <div class="inner-border">
                <div class="logo-text">Why<span>Finder</span></div>

                <div class="divider"></div>

                <div class="heading">Certificate of Completion</div>
                <div class="subheading">This certifies that</div>

                <div class="student-name">{{ $user->name }}</div>

                <div class="completion-text">has successfully completed the course</div>
                <div class="course-title">{{ $course->title }}</div>

                <div class="date">Issued on {{ $certificate->issued_at->format('F j, Y') }}</div>
                <div class="cert-number">Certificate #{{ $certificate->certificate_number }}</div>

                <div class="verify-url">
                    Verify at {{ url(route('certificates.verify', $certificate->certificate_number, false)) }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
