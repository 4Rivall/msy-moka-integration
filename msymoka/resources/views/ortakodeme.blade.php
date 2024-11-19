<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Test Formu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Ödeme Test Formu</h1>
    <form action="" method="POST">
        @csrf
        <h3>Payment Dealer Request</h3>
        <div class="mb-3">
            <label for="CardHolderFullName" class="form-label">Kart Sahibinin Adı</label>
            <input type="text" class="form-control" name="CardHolderFullName" value="MOKA TEST" required>
        </div>
        <div class="mb-3">
            <label for="CardNumber" class="form-label">Kart Numarası</label>
            <input type="text" class="form-control" name="CardNumber" value="5127 5411 2222 3332	" required>
        </div>
        <div class="mb-3">
            <label for="ExpMonth" class="form-label">Son Kullanma Ayı</label>
            <input type="text" class="form-control" name="ExpMonth" value="12" required>
        </div>
        <div class="mb-3">
            <label for="ExpYear" class="form-label">Son Kullanma Yılı</label>
            <input type="text" class="form-control" name="ExpYear" value="2025" required>
        </div>
        <div class="mb-3">
            <label for="CvcNumber" class="form-label">CVC</label>
            <input type="text" class="form-control" name="CvcNumber" value="000" required>
        </div>
        <div class="mb-3">
            <label for="Amount" class="form-label">Tutar</label>
            <input type="number" step="0.01" class="form-control" name="Amount" value="1" required>
        </div>
        <div class="mb-3">
            <label for="Currency" class="form-label">Para Birimi</label>
            <select class="form-control" name="Currency">
                <option value="TL" selected>TL</option>
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="ClientIP" class="form-label">Client IP</label>
            <input type="text" class="form-control" name="ClientIP" value="{{ request()->ip() }}">
        </div>

        <h3>Customer Information</h3>
        <div class="mb-3">
            <label for="FirstName" class="form-label">Ad</label>
            <input type="text" class="form-control" name="FirstName" value="Moka">
        </div>
        <div class="mb-3">
            <label for="LastName" class="form-label">Soyad</label>
            <input type="text" class="form-control" name="LastName" value="Ödeme">
        </div>
        <div class="mb-3">
            <label for="Email" class="form-label">E-posta</label>
            <input type="email" class="form-control" name="Email" value="test@moka.com">
        </div>
        <div class="mb-3">
            <label for="GsmNumber" class="form-label">Telefon</label>
            <input type="text" class="form-control" name="GsmNumber" value="5333333333">
        </div>
        <div class="mb-3">
            <label for="Address" class="form-label">Adres</label>
            <textarea class="form-control" name="Address">Test Adres</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Ödeme Testi Yap</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
