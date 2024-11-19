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
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header text-center bg-primary text-white">
                        <h4>Ödeme Formu</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{route('testCoPayment')}}" method="POST">
                            @csrf
                            
                            <!-- Ödeme Bilgileri -->
                            <h5 class="text-primary mb-3">Ödeme Bilgileri</h5>
                            <div class="mb-3">
                                <label for="Amount" class="form-label">Tutar</label>
                                <input type="number" step="0.01" name="Amount" id="Amount" class="form-control" placeholder="Ödeme tutarını giriniz" required>
                            </div>
                            <div class="mb-3">
                                <label for="Currency" class="form-label">Para Birimi</label>
                                <select name="Currency" id="Currency" class="form-select">
                                    <option value="TL">TL</option>
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="InstallmentNumber" class="form-label">Taksit Sayısı</label>
                                <input type="number" name="InstallmentNumber" id="InstallmentNumber" class="form-control" value="1" required>
                            </div>
                            <div class="mb-3">
                                <label for="Description" class="form-label">Açıklama</label>
                                <textarea name="Description" id="Description" class="form-control" rows="3" placeholder="Açıklama girin"></textarea>
                            </div>
    
                            <!-- Buyer Information -->
                            <h5 class="text-primary mb-3">Alıcı Bilgileri</h5>
                            <div class="mb-3">
                                <label for="BuyerFullName" class="form-label">Alıcı Adı Soyadı</label>
                                <input type="text" name="BuyerFullName" id="BuyerFullName" class="form-control" placeholder="Alıcının tam adını giriniz">
                            </div>
                            <div class="mb-3">
                                <label for="BuyerEmail" class="form-label">Alıcı Email</label>
                                <input type="email" name="BuyerEmail" id="BuyerEmail" class="form-control" placeholder="Alıcının email adresini giriniz">
                            </div>
                            <div class="mb-3">
                                <label for="BuyerGsmNumber" class="form-label">Alıcı Telefon</label>
                                <input type="tel" name="BuyerGsmNumber" id="BuyerGsmNumber" class="form-control" placeholder="5551234567" maxlength="10">
                            </div>
                            <div class="mb-3">
                                <label for="BuyerAddress" class="form-label">Alıcı Adresi</label>
                                <textarea name="BuyerAddress" id="BuyerAddress" class="form-control" rows="3" placeholder="Alıcının adresini girin"></textarea>
                            </div>
    
                            <!-- Customer Information -->
                            <h5 class="text-primary mb-3">Müşteri Bilgileri</h5>
                            <div class="mb-3">
                                <label for="DealerCustomerId" class="form-label">MOKA Müşteri Kodu</label>
                                <input type="text" name="DealerCustomerId" id="DealerCustomerId" value="6043391" class="form-control" placeholder="Müşteri kodunu giriniz">
                            </div>
                            <div class="mb-3">
                                <label for="CustomerCode" class="form-label">Müşteri Kodu</label>
                                <input type="text" name="CustomerCode" id="CustomerCode" class="form-control" placeholder="Müşteri kodunu giriniz">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="FirstName" class="form-label">Ad</label>
                                    <input type="text" name="FirstName" id="FirstName" class="form-control" placeholder="Ad">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="LastName" class="form-label">Soyad</label>
                                    <input type="text" name="LastName" id="LastName" class="form-control" placeholder="Soyad">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="Gender" class="form-label">Cinsiyet</label>
                                <select name="Gender" id="Gender" class="form-select">
                                    <option value="1">Erkek</option>
                                    <option value="2">Kadın</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="BirthDate" class="form-label">Doğum Tarihi</label>
                                <input type="date" name="BirthDate" id="BirthDate" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="GsmNumber" class="form-label">Telefon</label>
                                <input type="tel" name="GsmNumber" id="GsmNumber" class="form-control" placeholder="5551234567" maxlength="10">
                            </div>
                            <div class="mb-3">
                                <label for="Email" class="form-label">Email</label>
                                <input type="email" name="Email" id="Email" class="form-control" placeholder="Müşteri email adresini girin">
                            </div>
                            <div class="mb-3">
                                <label for="Address" class="form-label">Adres</label>
                                <textarea name="Address" id="Address" class="form-control" rows="3" placeholder="Müşteri adresini girin"></textarea>
                            </div>
    
                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Ödeme Yap</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
