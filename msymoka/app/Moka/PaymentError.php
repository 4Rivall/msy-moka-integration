<?php

namespace App\Moka;

class PaymentError
{
    /**
     * Ödeme işlemi hata kodları ve mesajları.
     *
     * @var array
     */
    protected static array $errors = [
        // Önceki hata kodları
        '000' => 'Genel Hata',
        '001' => 'Kart Sahibi Onayı Alınamadı',
        '002' => 'Limit Yetersiz',
        '003' => 'Kredi Kartı Numarası Geçerli Formatta Değil',
        '004' => 'Genel Red',
        '005' => 'Kart Sahibine Açık Olmayan İşlem',
        '006' => 'Kartın Son Kullanma Tarihi Hatali',
        '007' => 'Geçersiz İşlem',
        '008' => 'Bankaya Bağlanılamadı',
        '009' => 'Tanımsız Hata Kodu',
        '010' => 'Banka SSL Hatası',
        '011' => 'Manual Onay İçin Bankayı Arayınız',
        '012' => 'Kart Bilgileri Hatalı - Kart No veya CVV2',
        '013' => 'Visa MC Dışındaki Kartlar 3D Secure Desteklemiyor',
        '014' => 'Geçersiz Hesap Numarası',
        '015' => 'Geçersiz CVV',
        '016' => 'Onay Mekanizması Mevcut Değil',
        '017' => 'Sistem Hatası',
        '018' => 'Çalıntı Kart',
        '019' => 'Kayıp Kart',
        '020' => 'Kısıtlı Kart',
        '021' => 'Zaman Aşımı',
        '022' => 'Geçersiz İşyeri',
        '023' => 'Sahte Onay',
        '024' => '3D Onayı Alındı Ancak Para Karttan Çekilemedi',
        '025' => '3D Onay Alma Hatası',
        '026' => 'Kart Sahibi Banka veya Kart 3D-Secure Üyesi Değil',
        '027' => 'Kullanıcı Bu İşlemi Yapmaya Yetkili Değil',
        '028' => 'Fraud Olasılığı',
        '029' => 'Kartınız e-ticaret İşlemlerine Kapalıdır',

        // Yeni hata kodları
        'PaymentDealer.CheckPaymentDealerAuthentication.InvalidRequest' => 'CheckKey hatalı ya da nesne hatalı ya da JSON bozuk olabilir.',
        'PaymentDealer.CheckPaymentDealerAuthentication.InvalidAccount' => 'Böyle bir bayi bulunamadı.',
        'PaymentDealer.CheckPaymentDealerAuthentication.VirtualPosNotFound' => 'Bu bayi için sanal pos tanımı yapılmamış.',
        'PaymentDealer.CheckDealerPaymentLimits.DailyDealerLimitExceeded' => 'Bayi için tanımlı günlük limitlerden herhangi biri aşıldı.',
        'PaymentDealer.CheckDealerPaymentLimits.DailyCardLimitExceeded' => 'Gün içinde bu kart kullanılarak daha fazla işlem yapılamaz.',
        'PaymentDealer.CheckCardInfo.InvalidCardInfo' => 'Kart bilgilerinde hata var.',
        'PaymentDealer.DoDirectPayment3dRequest.InvalidRequest' => 'JSON objesi yanlış oluşturulmuş.',
        'PaymentDealer.DoDirectPayment3dRequest.RedirectUrlRequired' => '3D ödeme sonucunun döneceği RedirectURL verilmemiş.',
        'PaymentDealer.DoDirectPayment3dRequest.InvalidCurrencyCode' => 'Para birimi hatalı. (TL, USD, EUR şeklinde olmalı)',
        'PaymentDealer.DoDirectPayment3dRequest.InvalidInstallmentNumber' => 'Geçersiz taksit sayısı girilmiş 1-12 arası olmalıdır.',
        'PaymentDealer.DoDirectPayment3dRequest.InstallmentNotAvailableForForeignCurrencyTransaction' => 'Yabancı para ile taksit yapılamaz.',
        'PaymentDealer.DoDirectPayment3dRequest.ForeignCurrencyNotAvailableForThisDealer' => 'Bayinin yabancı parayla ödeme izni yok.',
        'PaymentDealer.DoDirectPayment3dRequest.PaymentMustBeAuthorization' => 'Ön otorizasyon tipinde ödeme gönderilmeli.',
        'PaymentDealer.DoDirectPayment3dRequest.AuthorizationForbiddenForThisDealer' => 'Bayinin ön otorizasyon tipinde ödeme gönderme izni yok.',
        'PaymentDealer.DoDirectPayment3dRequest.PoolPaymentNotAvailableForDealer' => 'Bayinin havuzlu ödeme gönderme izni yok.',
        'PaymentDealer.DoDirectPayment3dRequest.PoolPaymentRequiredForDealer' => 'Bayi sadece havuzlu ödeme gönderebilir.',
        'PaymentDealer.DoDirectPayment3dRequest.TokenizationNotAvailableForDealer' => 'Bayinin kart saklama izni yok.',
        'PaymentDealer.DoDirectPayment3dRequest.CardTokenCannotUseWithSaveCard' => 'Kart saklanmak isteniyorsa Token gönderilemez.',
        'PaymentDealer.DoDirectPayment3dRequest.CardTokenNotFound' => 'Gönderilen Token bulunamadı.',
        'PaymentDealer.DoDirectPayment3dRequest.OnlyCardTokenOrCardNumber' => 'Hem kart numarası hem de Token aynı anda verilemez.',
        'PaymentDealer.DoDirectPayment3dRequest.ChannelPermissionNotAvailable' => 'Bayinin bu kanaldan ödeme gönderme izni yok.',
        'PaymentDealer.DoDirectPayment3dRequest.IpAddressNotAllowed' => 'Bayinin IP kısıtlaması var, sadece önceden belirtilen IP den ödeme gönderebilir.',
        'PaymentDealer.DoDirectPayment3dRequest.VirtualPosNotAvailable' => 'Girilen kart için uygun sanal pos bulunamadı.',
        'PaymentDealer.DoDirectPayment3dRequest.ThisInstallmentNumberNotAvailableForVirtualPos' => 'Sanal Pos bu taksit sayısına izin vermiyor.',
        'PaymentDealer.DoDirectPayment3dRequest.ThisInstallmentNumberNotAvailableForDealer' => 'Bu taksit sayısı bu bayi için yapılamaz.',
        'PaymentDealer.DoDirectPayment3dRequest.DealerCommissionRateNotFound' => 'Bayiye bu sanal pos ve taksit için komisyon oranı girilmemiş.',
        'PaymentDealer.DoDirectPayment3dRequest.DealerGroupCommissionRateNotFound' => 'Üst bayiye bu sanal pos ve taksit için komisyon oranı girilmemiş.',
        'PaymentDealer.DoDirectPayment3dRequest.InvalidSubMerchantName' => 'Gönderilen bayi adı daha önceden Moka sistemine kaydedilmemiş.',
        'PaymentDealer.DoDirectPayment3dRequest.InvalidUnitPrice' => 'Satılan ürünler sepete eklendiyse, geçerli birim fiyatı girilmelidir.',
        'PaymentDealer.DoDirectPayment3dRequest.InvalidQuantityValue' => 'Satılan ürünler sepete eklendiyse, geçerli adet girilmelidir.',
        'PaymentDealer.DoDirectPayment3dRequest.BasketAmountIsNotEqualPaymentAmount' => 'Satılan ürünler sepete eklendiyse, sepet tutarı ile ödeme tutarı eşleşmelidir.',
        'PaymentDealer.DoDirectPayment3dRequest.BasketProductNotFoundInYourProductList' => 'Satılan ürünler sepete eklendiyse, geçerli ürün seçilmelidir.',
        'PaymentDealer.DoDirectPayment3dRequest.MustBeOneOfDealerProductIdOrProductCode' => 'Satılan ürünler sepete eklendiyse, ürün kodu veya moka ürün ID si girilmelidir.',
        'ClientWebPos.CreateWebPosRequest.CustomerInfoIsRequiredToSaveTheCard' => 'Kartı kaydetmek için Costumer Info gereklidir.',
        'EX' => 'Beklenmeyen bir hata oluştu',
    ];

    /**
     * Hata koduna göre mesaj döndür.
     *
     * @param string $code
     * @return string
     */
    public static function getMessage(string $code): string
    {
        return self::$errors[$code] ?? 'Bilinmeyen Hata';
    }
}
