<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <title>Your Nexora Verification Code</title>
  <!--[if mso]>
  <noscript>
    <xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml>
  </noscript>
  <![endif]-->
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', Arial, sans-serif;
      background-color: #0A1A0F;
      margin: 0;
      padding: 0;
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
    }

    .wrapper {
      width: 100%;
      background-color: #0A1A0F;
      padding: 48px 16px;
    }

    .container {
      max-width: 560px;
      margin: 0 auto;
      background: #0F1F14;
      border-radius: 20px;
      overflow: hidden;
      border: 1px solid #1C3824;
      box-shadow: 0 24px 80px rgba(0,255,102,0.08);
    }

    /* ── Header ── */
    .header {
      background: linear-gradient(135deg, #071209 0%, #0D2115 50%, #071209 100%);
      padding: 40px 40px 32px;
      text-align: center;
      border-bottom: 1px solid #1C3824;
      position: relative;
      overflow: hidden;
    }

    .header::before {
      content: '';
      position: absolute;
      top: -60px; left: 50%;
      transform: translateX(-50%);
      width: 340px; height: 160px;
      background: radial-gradient(ellipse, rgba(0,255,102,0.12) 0%, transparent 70%);
      pointer-events: none;
    }



    .brand-tagline {
      font-size: 11.5px;
      color: #3A6645;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      margin-top: 4px;
    }

    /* ── Body ── */
    .body {
      padding: 44px 40px;
    }

    .greeting {
      font-size: 20px;
      font-weight: 700;
      color: #EDFFF4;
      margin-bottom: 10px;
    }

    .intro {
      font-size: 14.5px;
      color: #6B8E78;
      line-height: 1.65;
      margin-bottom: 36px;
    }

    /* OTP box */
    .otp-wrapper {
      text-align: center;
      margin-bottom: 36px;
    }

    .otp-label {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: #3A6645;
      margin-bottom: 14px;
    }

    .otp-box {
      display: inline-block;
      background: linear-gradient(135deg, #0D2115, #112818);
      border: 1.5px solid #00FF66;
      border-radius: 16px;
      padding: 20px 48px;
      box-shadow: 0 0 40px rgba(0,255,102,0.18), inset 0 0 30px rgba(0,255,102,0.04);
    }

    .otp-code {
      font-family: 'Courier New', 'Courier', monospace;
      font-size: 42px;
      font-weight: 900;
      letter-spacing: 10px;
      color: #00FF66;
      text-shadow: 0 0 30px rgba(0,255,102,0.5);
      /* Fallback for email clients that don't support letter-spacing on last char */
      padding-right: 10px;
    }

    /* Timer notice */
    .timer-row {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      background: rgba(0,255,102,0.05);
      border: 1px solid #1C3824;
      border-radius: 10px;
      padding: 12px 20px;
      margin-bottom: 32px;
    }

    .timer-icon {
      font-size: 16px;
    }

    .timer-text {
      font-size: 13px;
      color: #5A8A6A;
    }

    .timer-text strong {
      color: #00FF66;
      font-weight: 700;
    }

    /* Divider */
    .divider {
      border: none;
      border-top: 1px solid #1C3824;
      margin: 32px 0;
    }

    /* Security notice */
    .security-block {
      background: rgba(255, 60, 60, 0.05);
      border: 1px solid rgba(255,60,60,0.18);
      border-radius: 10px;
      padding: 16px 20px;
      margin-bottom: 0;
    }

    .security-title {
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      color: #E05555;
      margin-bottom: 6px;
    }

    .security-text {
      font-size: 12.5px;
      color: #8A6060;
      line-height: 1.6;
    }

    /* ── Footer ── */
    .footer {
      background: #071209;
      padding: 28px 40px;
      text-align: center;
      border-top: 1px solid #1C3824;
    }

    .footer-links {
      margin-bottom: 14px;
    }

    .footer-links a {
      font-size: 12px;
      color: #3A6645;
      text-decoration: none;
      margin: 0 10px;
    }

    .footer-copy {
      font-size: 11.5px;
      color: #2A4433;
      line-height: 1.6;
    }

    .footer-copy strong {
      color: #3A6645;
    }

    /* Snake icon pattern (decorative) */
    .pattern-row {
      text-align: center;
      padding: 0 40px 0;
      margin-bottom: 32px;
    }

    .pattern-row span {
      display: inline-block;
      width: 6px; height: 6px;
      background: #1C3824;
      border-radius: 50%;
      margin: 0 4px;
    }

    .pattern-row span:nth-child(2),
    .pattern-row span:nth-child(4) {
      background: #2A5C3A;
    }

    .pattern-row span:nth-child(3) {
      background: #00FF66;
      width: 8px; height: 8px;
      box-shadow: 0 0 8px rgba(0,255,102,0.6);
    }

    @media only screen and (max-width: 600px) {
      .body { padding: 32px 24px; }
      .header { padding: 32px 24px 24px; }
      .footer { padding: 24px; }
      .otp-code { font-size: 34px; letter-spacing: 6px; }
      .otp-box { padding: 16px 28px; }
    }
  </style>
</head>
<body>
<div class="wrapper">
  <div class="container">

    <!-- Header / Brand -->
    <div class="header">
      <img src="{{ asset('images/nexor.png') }}" alt="Nexora Logo" style="max-height: 80px; object-fit: contain; filter: drop-shadow(0 4px 12px rgba(0, 255, 102, 0.4));">
      <div class="brand-tagline">Snake Identification &amp; Rescue Platform</div>
    </div>

    <!-- Dots pattern -->
    <div class="pattern-row" style="padding-top: 28px;">
      <span></span><span></span><span></span><span></span><span></span>
    </div>

    <!-- Main Body -->
    <div class="body">

      <div class="greeting">Verify your identity</div>
      <p class="intro">
        We received a request to access your Nexora account. Use the verification
        code below to complete the process. Do not share this code with anyone.
      </p>

      <!-- OTP Code -->
      <div class="otp-wrapper">
        <div class="otp-label">&#128274;&nbsp; Your one-time code</div>
        <div class="otp-box">
          <div class="otp-code">{{ $otp }}</div>
        </div>
      </div>

      <!-- Expiry timer -->
      <div class="timer-row">
        <span class="timer-icon">&#9201;</span>
        <span class="timer-text">
          This code expires in <strong>10 minutes</strong>.
          Do not refresh or share it.
        </span>
      </div>

      <hr class="divider">

      <!-- Security notice -->
      <div class="security-block">
        <div class="security-title">&#128680;&nbsp; Security Notice</div>
        <p class="security-text">
          If you did not request this code, your account may be at risk.
          Please ignore this email and consider changing your password.
          Nexora will never ask for your code via phone or chat.
        </p>
      </div>

    </div><!-- /.body -->

    <!-- Footer -->
    <div class="footer">
      <div class="footer-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
        <a href="#">Support</a>
      </div>
      <p class="footer-copy">
        &copy; {{ date('Y') }} <strong>Nexora Platform</strong>. All rights reserved.<br>
        This is an automated message — please do not reply directly to this email.
      </p>
    </div>

  </div><!-- /.container -->
</div><!-- /.wrapper -->
</body>
</html>
