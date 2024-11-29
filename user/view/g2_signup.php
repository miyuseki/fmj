<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/g2_style.css">
    <title>新規登録画面</title>
</head>

<body>
    <h1>FMJ</h1>
    <form id="registrationForm" action="../server/signup_user_process.php" method="post">
        <fieldset>
            <h2>ログイン情報</h2>
            <input type=" email" id="email" name="email" placeholder="メールアドレス" required>
            <div id="emailError" class="error"></div>
            <input type="email" id="emailCheck" name="email_check" placeholder="メールアドレス(確認)" required>
            <div id="emailMatchError" class="error"></div>
            <input type="password" id="password" name="password" placeholder="パスワード" required>
            <div id="passwordError" class="error"></div>
            <input type="password" id="passwordCheck" name="password_check" placeholder="パスワード(確認)" required>
            <div id="passwordMatchError" class="error"></div>
        </fieldset>
    
        <fieldset>
            <h2>住所</h2>
            <div class="double_row">
                <input type="text" name="last_kanji" placeholder="姓" required>
                <input type="text" name="first_kanji" placeholder="名" required>
            </div>
            <div class="double_row">
                <input type="text" name="last_kana" placeholder="セイ" required>
                <input type="text" name="first_kana" placeholder="メイ" required>
            </div>
    
            <input type="text" name="zip_code" placeholder="郵便番号" required><br>
            <input type="text" name="prefectures" placeholder="都道府県 市区町村" required><br>
            <input type="text" name="street_address" placeholder="番地" required><br>
            <input type="text" name="apartment_name" placeholder="マンション 部屋番号"><br>
        </fieldset>
    
        <button type="submit">登録</button>
    </form>
    <footer></footer>
    
    <script>
        const form = document.getElementById('registrationForm');
        const email = document.getElementById('email');
        const emailCheck = document.getElementById('emailCheck');
        const password = document.getElementById('password');
        const passwordCheck = document.getElementById('passwordCheck');
        const emailError = document.getElementById('emailError');
        const emailMatchError = document.getElementById('emailMatchError');
        const passwordError = document.getElementById('passwordError');
        const passwordMatchError = document.getElementById('passwordMatchError');
    
        form.addEventListener('submit', (e) => {
            let valid = true;
    
            // メールアドレス形式のバリデーション
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                emailError.textContent = '正しいメールアドレスを入力してください。';
                valid = false;
            } else {
                emailError.textContent = '';
            }
    
            // メールアドレスの一致確認
            if (email.value !== emailCheck.value) {
                emailMatchError.textContent = 'メールアドレスが一致しません。';
                valid = false;
            } else {
                emailMatchError.textContent = '';
            }
    
            // パスワード形式のバリデーション (英数字8文字以上)
            const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
            if (!passwordRegex.test(password.value)) {
                passwordError.textContent = 'パスワードは英数字8文字以上で入力してください。';
                valid = false;
            } else {
                passwordError.textContent = '';
            }
    
            // パスワードの一致確認
            if (password.value !== passwordCheck.value) {
                passwordMatchError.textContent = 'パスワードが一致しません。';
                valid = false;
            } else {
                passwordMatchError.textContent = '';
            }
    
            if (!valid) {
                e.preventDefault(); // フォーム送信を防止
            }
        });
    </script>
    </body>
    
    </html>