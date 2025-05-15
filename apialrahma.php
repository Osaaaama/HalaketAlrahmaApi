<?php
// تفعيل عرض الأخطاء للمساعدة في التشخيص
error_reporting(E_ALL);
ini_set('display_errors', 1);

// وضع headers أولاً قبل أي إخراج
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json; charset=utf-8');

// التعامل مع طلب OPTIONS بشكل سريع
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// نتحقق من أن الطلب هو GET (اختياري، يعتمد على الحاجة)
if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    http_response_code(405); // طريقة غير مسموح بها
    echo json_encode(['error' => 'طريقة غير مسموح بها'], JSON_UNESCAPED_UNICODE);
    exit();
}

// بدء الاتصال بقاعدة البيانات
$conn = new mysqli('sql112.infinityfree.com', 'if0_38216407', 'OsamaA7omd', 'if0_38216407_al_halaka');
$conn->set_charset("utf8mb4");

// التحقق من الاتصال
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => "فشل الاتصال: " . $conn->connect_error], JSON_UNESCAPED_UNICODE);
    $conn->close();
    exit();
}

// تنفيذ استعلام البيانات
$result = $conn->query("SELECT * FROM messages ORDER BY updated_at DESC");

// التحقق من نجاح الاستعلام
if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => "خطأ في تنفيذ الاستعلام: " . $conn->error], JSON_UNESCAPED_UNICODE);
    $conn->close();
    exit();
}

// جلب البيانات
$messages = $result->fetch_all(MYSQLI_ASSOC);

// إرسال البيانات في النهاية
echo json_encode($messages ? $messages : [], JSON_UNESCAPED_UNICODE);

// إغلاق الاتصال
$conn->close();
exit();
?>