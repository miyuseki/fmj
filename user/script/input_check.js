// メールアドレスの一致チェック
document.getElementById('mail_address_check').addEventListener('input', function() {
    const mailAddress = document.getElementById('mail_address').value;
    const mailAddressCheck = document.getElementById('mail_address_check').value;
    let validation = document.getElementById('email_validation');

    // メールアドレスが一致していない場合
    if (mailAddress !== mailAddressCheck) {
        validation.innerHTML = '<p>メールアドレスが一致しません。</p>';
    } else {
        validation.innerHTML = ''; // 一致すればエラーメッセージを消去
    }
});

// パスワードの一致チェック
document.getElementById('password_check').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const passwordCheck = document.getElementById('password_check').value;
    let validation = document.getElementById('password_validation');

    // パスワードが一致していない場合
    if (password !== passwordCheck) {
        validation.innerHTML = '<p>パスワードが一致しません。</p>';
    } else {
        // メールアドレスのエラーがあればそれを優先して表示
        if (!document.getElementById('mail_address_check').value || document.getElementById('mail_address').value !== document.getElementById('mail_address_check').value) {
            validation.innerHTML = '<p>メールアドレスが一致しません。</p>';
        } else {
            validation.innerHTML = ''; // 一致すればエラーメッセージを消去
        }
    }
});

// フォーム送信前に最終確認
document.getElementById('signup_form').addEventListener('submit', function(event) {
    const mailAddress = document.getElementById('mail_address').value;
    const mailAddressCheck = document.getElementById('mail_address_check').value;
    const password = document.getElementById('password').value;
    const passwordCheck = document.getElementById('password_check').value;

    let validation = document.getElementById('validation');
    validation.innerHTML = '';  // 送信前にエラーメッセージをリセット

    // メールアドレスが一致しない場合
    if (mailAddress !== mailAddressCheck) {
        validation.innerHTML = '<p>メールアドレスが一致しません。</p>';
        event.preventDefault();  // フォーム送信をキャンセル
    }

    // パスワードが一致しない場合
    if (password !== passwordCheck) {
        validation.innerHTML += '<p>パスワードが一致しません。</p>';
        event.preventDefault();  // フォーム送信をキャンセル
    }
});
