@extends('layouts.cmspage')
<style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        h2 {
            margin-top: 20px;
        }

        p {
            margin-bottom: 10px;
        }
    </style>
@section('content')
@php
$aboutus = App\Models\CMSpage::where('pagename','Privacy Policy')->where('status','Active')->first();
@endphp
<section>
    <div style="background-image: url(images/Rectangle.png);">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-p" style="top:12px;">
                    <h1>Privacy Policy</h1>
    <br>
    <p><strong>Last updated: [09 November 2023]</strong></p>

    <p>ServiceBolt is committed to protecting the privacy of its users. This Privacy Policy outlines how your personal information is collected, used, and shared when you use the ServiceBolt companion application.</p>

    <h2>Information We Collect</h2>

    <p><strong>Personal Information:</strong> This may include your name, email address, phone number, and other contact information.</p>

    <p><strong>Usage Information:</strong> We collect information about how you use the application, including the features you interact with and the time spent on each.</p>

    <p><strong>Device Information:</strong> We may collect information about the device you're using, including the device type, operating system, and unique device identifiers.</p>

    <h2>How We Use Your Information</h2>

    <p>We use the information collected to:</p>

    <ul>
        <li>Provide and maintain the ServiceBolt companion application.</li>
        <li>Notify you about important updates or changes to the application.</li>
        <li>Improve and optimize the application's performance and user experience.</li>
        <li>Respond to your inquiries, comments, or questions.</li>
        <li>Send you promotional information, with your consent.</li>
    </ul>

    <h2>Sharing Your Information</h2>

    <p>We do not sell, trade, or otherwise transfer your personal information to outside parties. However, we may share your information with third-party service providers who assist us in operating our application or conducting our business.</p>

    <h2>Security</h2>

    <p>We take reasonable measures to protect the confidentiality and security of your personal information. However, no method of transmission over the internet or electronic storage is completely secure, and we cannot guarantee absolute security.</p>

    <h2>Your Choices</h2>

    <p>You can choose not to provide certain information, but this may limit your ability to use some features of the ServiceBolt companion application.</p>

    <h2>Changes to This Privacy Policy</h2>

    <p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page.</p>

    <h2>Contact Us</h2>

    <p>If you have any questions or concerns about this Privacy Policy, please contact us at <a href="mailto:serviceboltdev@gmail.com">serviceboltdev@gmail.com</a>.</p>

    <p>By using the ServiceBolt companion application, you agree to the terms of this Privacy Policy.</p>
                </div>
            </div>
            
        </div>
    </div>
</div>
</section>



<section>
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="footer-p">
                        <img src="images/logo.png" style="width: 230px;height: auto;">
                        <p style="color: #81878F;font-size: 12px;  margin: 12px 0px;">2021 Service Bolt - All right reserved</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection