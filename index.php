<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>EHSSG Email Signature Generator</title>
</head>
<body>
    <nav class="navbar" data-bs-theme="dark" style="background-color:#c55c04c7;">
        <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">EHSSG Email Signature Generator</span>
        <i class="bi bi-box-arrow-in-left" style="font-size:1.5rem; color:#fff; margin-right:2rem;">&nbsp;<span style="font-size:1rem;"><a href="https://ehssg.org" style="text-decoration:none; color:#fff;">Back to ehssg.org</a></span></i>
        </div>
    </nav>
    <div class="container-fluid" style="background-color:#c55c03; height:10px;"></div>
    <div class="container">
        <div class="row" style="margin-top:50px; margin-bottom:50px;">
            <div class="col" style="padding:20px;">
                <?php
        // variables
        $name_text = $position_text = $country_code_text = $phone_text = $email_text = "";
        $organization_name_text = "EHSSG";
        $web_url_text = "https://ehssg.org";
        if ($_SERVER ["REQUEST_METHOD"] == "POST") {
            $name_text = $_POST['name_text'];
            $position_text = $_POST['position_text'];
            $country_code_text = $_POST['country_code_text'];
            $phone_text = $_POST['phone_text'];
            $email_text = $_POST['email_text'];
            
            // validation
            if (empty($name_text)) {
                $errors[] = 'Name is required';
            } elseif (!preg_match('/^[a-zA-Z ,.]+$/', $name_text)) {
                $errors[] = 'Name can only contain letters and spaces';
            } else if (empty($position_text)) {
                $errors[]= 'Position is required';
            } elseif (!preg_match('/^[a-zA-Z ]+$/', $position_text)) {
                $errors[] = 'Position can only contain letters and spaces';
            } elseif ($country_code_text == "") {
                $errors[] = 'Please select country code';
            }
            // Throw error if exists
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo "<p>$error</p>";
                }
                echo '<button type="button" class="btn btn-secondary mt-3" onclick="window.location.href=\'https://ehssg.org/signature-generator\'">Back</button>';
                exit;
            }
            // background email signature template
            $image = imagecreatefrompng('template/email-signature-base-template.png');

            // text color
            $name_text_Color = imagecolorallocate($image, 38, 38, 38);
            $position_text_Color = imagecolorallocate($image, 38, 38, 38);
            $country_code_text_Color = imagecolorallocate($image, 38, 38, 38);
            $phone_text_Color = imagecolorallocate($image, 38, 38, 38);
            $email_text_Color = imagecolorallocate($image, 38, 38, 38);
            $organization_text_Color = imagecolorallocate($image, 38, 38, 38);
            $web_url_text_Color = imagecolorallocate($image, 38, 38, 38);

            // font path
            $font_path = 'fonts/RobotoSlab-Regular.ttf';

            // place text on base template
            imagettftext($image, 20, 0, 100, 55, $name_text_Color, $font_path, $name_text);
            imagettftext($image, 20, 0, 100, 115, $position_text_Color, $font_path, $position_text);
            imagettftext($image, 20, 0, 100, 210, $country_code_text_Color, $font_path, $country_code_text);
            imagettftext($image, 20, 0, 150, 210, $phone_text_Color, $font_path, $phone_text);
            imagettftext($image, 20, 0, 100, 270, $email_text_Color, $font_path, $email_text . '@ehssg.org');
            imagettftext($image, 20, 0, 100, 155, $organization_text_Color, $font_path, $organization_name_text);
            imagettftext($image, 20, 0, 100, 310, $web_url_text_Color, $font_path, $web_url_text);

            // send image to browser
            ob_start();
            imagepng($image);
            $image = ob_get_contents();
            ob_end_clean();
            echo '<div>';
            echo '<img src="data:image/png;base64, '.base64_encode($image).'" style="zoom:50%; border:1px solid #d0d0d0;" />';
            echo '<br /><br/>';
            echo '<a href="data:image/png;base64,' . base64_encode($image) . '" download="' . $name_text . '_signature.png" class="btn btn-outline-primary">Download</a>';
            echo '</div>';
        }
    ?>
            </div>
            <div class="col" style="background-color:#e6e6e6;border: 1px solid #d0d0d0;border-radius:8px;box-shadow: 3px 3px 10px #d4d1d1;">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" autocomplete="off" style="padding:30px;">
                    <label for="name_text" class="form-label">Name</label>
                    <input type=text name=name_text value="<?php echo $name_text;?>" placeholder="Please enter your name" title="Name" class="form-control" required><br />
                    <label for="position_text" class="form-label">Position</label>
                    <input type=text name=position_text value="<?php echo $position_text;?>" placeholder="Please enter your position" title="Position" class="form-control" required><br />
                    <label for="country_code" class="form-label">Country code</label>
                    <select name="country_code_text" class="form-select" required>
                        <option value="">Please select country code</option>
                        <option value="+66" <?php if ($country_code_text == '+66') echo 'selected'; ?>>+66 (Thailand)</option>
                        <option value="+95" <?php if ($country_code_text == '+95') echo 'selected'; ?>>+95 (Burma)</option>
                    </select>
                    <label for="phone_text" class="form-label">Phone</label>
                    <input type=text name=phone_text value="<?php echo $phone_text;?>" placeholder="Please enter your number" title="Phone" class="form-control" aria-label="Email" required><br />
                    <label for="email_text" class="form-label">Email</label>
                    <div class="input-group">
                    <input type=text name=email_text value="<?php echo $email_text;?>" placeholder="Email" title="Email" class="form-control" required><br />
                    <span class="input-group-text">@ehssg.org</span>
                    </div><br />
                    <input type=submit class="btn btn-primary" value="Create Signature">
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // The form was submitted via POST, so show the button
                    echo '<button type="button" class="btn btn-secondary mt-3" onclick="window.location.href=\'https://ehssg.org/signature-generator\'">Clear Signature</button>';
                    }
                    ?>
                </form>
            </div>
            <div class="col" style="padding:30px;">
                <p>The Email Signature Generator is for EHSSG staff to generate uniform email signatures based on the provided information.<br />
                The signatures are not stored on the server and got deleted once you close this page.<br/>Please download the generated image and import to the mail client of your choice.</p><br />
                <h5>For EHSSG use only.</h5>
            </div>
        </div>
    </div>
</body>
</html>