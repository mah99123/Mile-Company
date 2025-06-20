# نظام إدارة المنصات المتعددة

نظام شامل لإدارة ثلاث منصات مختلفة مع دعم كامل للغة العربية واتجاه النص من اليمين إلى اليسار (RTL).

## المنصات المدعومة

### 1. منصة ميم (Meym) - إدارة الحملات الإعلانية
- إدارة الحملات الإعلانية
- تتبع الميزانيات والتواريخ
- نظام التحديثات والرسائل
- تكامل مع واتساب

### 2. محمد فون تك (PhoneTech) - متجر الإلكترونيات
- إدارة المنتجات والمخزون
- نظام المبيعات والأقساط
- إدارة الموردين
- تتبع المدفوعات والتذكيرات

### 3. شركة الميم - استيراد السيارات
- إدارة عمليات الاستيراد
- تتبع الشحنات والحاويات
- إدارة المزادات والعقود
- تقارير الأرباح والخسائر

### 4. الوحدة العامة
- إدارة المستخدمين والأدوار
- النظام المحاسبي
- التقارير المالية الشاملة
- لوحة التحكم الموحدة

## المتطلبات التقنية

- PHP 8.1+
- Laravel 10.x
- MySQL 8.0+ (UTF8-MB4)
- Composer
- Node.js & NPM

## التثبيت

### 1. استنساخ المشروع
\`\`\`bash
git clone <repository-url>
cd multi-platform-app
\`\`\`

### 2. تثبيت التبعيات
\`\`\`bash
composer install
npm install
\`\`\`

### 3. إعداد البيئة
\`\`\`bash
cp .env.example .env
php artisan key:generate
\`\`\`

### 4. إعداد قاعدة البيانات
قم بتحديث ملف `.env` بمعلومات قاعدة البيانات:
\`\`\`env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=multi_platform_app
DB_USERNAME=root
DB_PASSWORD=your_password
\`\`\`

### 5. تشغيل الترحيلات والبذور
\`\`\`bash
php artisan migrate --seed
\`\`\`

### 6. تشغيل الخادم
\`\`\`bash
php artisan serve
\`\`\`

## بيانات الدخول الافتراضية

### مدير النظام
- البريد الإلكتروني: `admin@multiplatform.com`
- كلمة المرور: `password`

### مدير منصة ميم
- البريد الإلكتروني: `meym@multiplatform.com`
- كلمة المرور: `password`

### مدير محمد فون تك
- البريد الإلكتروني: `phonetech@multiplatform.com`
- كلمة المرور: `password`

### مدير استيراد السيارات
- البريد الإلكتروني: `carimport@multiplatform.com`
- كلمة المرور: `password`

## الميزات الرئيسية

### الأمان
- نظام الأدوار والصلاحيات باستخدام Spatie Laravel Permission
- حماية من CSRF و XSS
- تشفير كلمات المرور
- تحديد محاولات تسجيل الدخول

### التقارير
- تقارير مالية شاملة
- تقارير المبيعات والمخزون
- تقارير الحملات الإعلانية
- تصدير PDF للتقارير

### التنبيهات والتذكيرات
- تذكيرات الأقساط المستحقة
- تنبيهات المخزون المنخفض
- تنبيهات انتهاء الحملات

### التكامل
- تكامل مع واتساب للرسائل
- تصدير البيانات إلى Excel
- إنشاء فواتير PDF

## هيكل المشروع

\`\`\`
app/
├── Http/Controllers/
│   ├── Admin/           # إدارة النظام
│   ├── Meym/           # منصة ميم
│   ├── PhoneTech/      # محمد فون تك
│   └── CarImport/      # استيراد السيارات
├── Models/             # نماذج البيانات
├── Console/Commands/   # أوامر الجدولة
└── Services/          # الخدمات المساعدة

resources/views/
├── layouts/           # القوالب الأساسية
├── meym/             # واجهات منصة ميم
├── phonetech/        # واجهات محمد فون تك
├── carimport/        # واجهات استيراد السيارات
├── admin/            # واجهات إدارة النظام
└── reports/          # واجهات التقارير
\`\`\`

## الجدولة والمهام

### التذكيرات اليومية
\`\`\`bash
php artisan schedule:run
\`\`\`

يتم تشغيل المهام التالية يومياً:
- فحص الأقساط المستحقة وإرسال التذكيرات
- تحديث حالات الحملات المنتهية
- إنشاء تقارير يومية

## التخصيص والتطوير

### إضافة منصة جديدة
1. إنشاء Migration للجداول المطلوبة
2. إنشاء Models مع العلاقات
3. إنشاء Controllers للعمليات
4. إنشاء Views للواجهات
5. إضافة Routes في web.php
6. إضافة Permissions في PermissionSeeder

### إضافة تقرير جديد
1. إضافة method في ReportController
2. إنشاء View للتقرير
3. إضافة Route في reports group
4. إضافة رابط في قائمة التقارير

## الدعم والمساعدة

للحصول على المساعدة أو الإبلاغ عن مشاكل:
1. راجع الوثائق أولاً
2. تحقق من ملفات السجل في `storage/logs/`
3. تأكد من صحة إعدادات قاعدة البيانات
4. تحقق من صلاحيات المستخدم

## الترخيص

هذا المشروع مرخص تحت رخصة MIT.
