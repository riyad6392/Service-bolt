@extends('layouts.cmspage')
<title>Contact Us - ServiceBolt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input,
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
@section('content')
<section>
    <div style="background-image: url(images/Rectangle.png);">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-p" style="top:91px;">
                     <h1>Contact Us</h1>

                        <form action="contact">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" required>

                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>

                            <label for="message">Message:</label>
                            <textarea id="message" name="message" rows="4" required></textarea>
                            <div style="text-align:center;padding: 18px 0 100px 0">
                                <button type="submit">Submit</button></div>
                            
                        </form>
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