<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Acesso - GuildaByte</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #050507;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #aaa4bc;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 40px auto;
            background-color: #101018;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #26215c;
        }
        .header {
            background-color: #7f77dd;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            color: #ffffff;
            font-size: 28px;
            letter-spacing: 1px;
            font-weight: 800;
        }
        .header p {
            margin: 5px 0 0;
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        .content p.welcome {
            font-size: 16px;
            color: #f6f4ff;
            margin-bottom: 30px;
        }
        .content p.welcome span {
            color: #92ffcb;
            font-weight: bold;
        }
        .code-box {
            background-color: #0a0a10;
            border: 1px solid #26215c;
            border-radius: 8px;
            padding: 20px;
            margin: 0 auto 30px;
            max-width: 300px;
        }
        .code-text {
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 8px;
            color: #92ffcb;
            margin: 0;
            font-family: monospace;
        }
        .expiry {
            font-size: 12px;
            color: #aaa4bc;
            margin-bottom: 5px;
        }
        .expiry strong {
            color: #ff7893;
        }
        .warning {
            font-size: 11px;
            color: #7f77dd;
        }
        .footer {
            padding: 20px;
            text-align: center;
            font-size: 10px;
            color: #534ab7;
            border-top: 1px solid #26215c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>GuildaByte</h1>
            <p>Sua plataforma de tecnologia</p>
        </div>
        <div class="content">
            <p class="welcome">Bem-vindo(a) à <span>GuildaByte</span></p>
            <p style="font-size: 14px; margin-bottom: 15px;">Seu código de acesso:</p>
            
            <div class="code-box">
                <p class="code-text">{{ implode(' ', str_split($code)) }}</p>
            </div>
            
            <p class="expiry">Expira em <strong>{{ $expiresInMinutes }} minutos</strong></p>
            <p class="warning">Não compartilhe este código com ninguém.</p>
        </div>
        <div class="footer">
            Se você não solicitou este código, ignore este e-mail.
        </div>
    </div>
</body>
</html>
