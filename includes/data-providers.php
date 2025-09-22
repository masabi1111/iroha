<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

/**
 * Executes a prepared statement and returns all rows or null on failure.
 *
 * @param array<int, mixed> $params
 * @return array<int, array<string, mixed>>|null
 */
function runQuery(string $sql, array $params = []): ?array
{
    $pdo = getDbConnection();
    if ($pdo === null) {
        return null;
    }

    try {
        $statement = $pdo->prepare($sql);
        $statement->execute($params);
        /** @var array<int, array<string, mixed>> $rows */
        $rows = $statement->fetchAll();
        return $rows;
    } catch (\PDOException $exception) {
        error_log('Database query failed: ' . $exception->getMessage());
        return null;
    }
}

function formatDuration(?int $minutes, string $fallback = ''): string
{
    if ($minutes === null || $minutes <= 0) {
        return $fallback;
    }

    if ($minutes < 60) {
        return sprintf('%d دقيقة', $minutes);
    }

    $hours = intdiv($minutes, 60);
    $remainingMinutes = $minutes % 60;

    $parts = [];
    if ($hours > 0) {
        $parts[] = sprintf('%d ساعة', $hours);
    }

    if ($remainingMinutes > 0) {
        $parts[] = sprintf('%d دقيقة', $remainingMinutes);
    }

    return implode(' و', $parts);
}

/**
 * @return array<int, array<string, string>>
 */
function getDashboardMetrics(): array
{
    $fallback = [
        [
            'label' => 'التقدم العام',
            'value' => '52%',
            'trend' => '+6% هذا الأسبوع',
            'trend_type' => 'up',
        ],
        [
            'label' => 'حروف الهيراغانا المتقنة',
            'value' => '38/46',
            'trend' => 'تمت إضافة 4 حروف جديدة',
            'trend_type' => 'up',
        ],
        [
            'label' => 'المفردات الجديدة',
            'value' => '24 كلمة',
            'trend' => 'ثابتة',
            'trend_type' => 'neutral',
        ],
        [
            'label' => 'أيام الممارسة المتتالية',
            'value' => '7 أيام',
            'trend' => 'حافظ على السلسلة!',
            'trend_type' => 'up',
        ],
    ];

    $rows = runQuery(
        'SELECT label, value_text, trend_text, trend_type
         FROM dashboard_metrics
         ORDER BY sort_order, id'
    );

    if (empty($rows)) {
        return $fallback;
    }

    return array_map(
        static fn (array $row): array => [
            'label' => (string)($row['label'] ?? ''),
            'value' => (string)($row['value_text'] ?? ''),
            'trend' => (string)($row['trend_text'] ?? ''),
            'trend_type' => (string)($row['trend_type'] ?? 'neutral'),
        ],
        $rows
    );
}

/**
 * @return array<int, array<string, string>>
 */
function getDashboardLessons(): array
{
    $fallback = [
        [
            'title' => 'مقدمة الهيراغانا',
            'level' => 'أساسيات',
            'chip_variant' => 'design',
            'skill' => 'التعرف على الحروف あ い う',
            'duration' => '12 دقيقة',
            'status' => 'مكتمل',
            'status_variant' => 'done',
        ],
        [
            'title' => 'تحية الصباح والمساء',
            'level' => 'محادثة',
            'chip_variant' => 'dev',
            'skill' => 'جمل はじめまして و おはよう',
            'duration' => '18 دقيقة',
            'status' => 'قيد الدراسة',
            'status_variant' => 'in-progress',
        ],
        [
            'title' => 'الكانا في الكلمات',
            'level' => 'مفردات',
            'chip_variant' => 'analytics',
            'skill' => 'ربط الحروف بكلمات يومية',
            'duration' => '15 دقيقة',
            'status' => 'للمراجعة',
            'status_variant' => 'pending',
        ],
        [
            'title' => 'أرقام حتى 20',
            'level' => 'قواعد',
            'chip_variant' => 'brand',
            'skill' => 'أنماط العد اليابانية',
            'duration' => '10 دقائق',
            'status' => 'قيد التقدم',
            'status_variant' => 'in-progress',
        ],
    ];

    $rows = runQuery(
        'SELECT title, level_label, level_variant, skill, duration_text, duration_minutes, status_label, status_variant
         FROM student_lessons
         ORDER BY sort_order, id'
    );

    if (empty($rows)) {
        return $fallback;
    }

    return array_map(
        static function (array $row): array {
            $duration = (string)($row['duration_text'] ?? '');
            if ($duration === '') {
                $duration = formatDuration(
                    isset($row['duration_minutes']) ? (int) $row['duration_minutes'] : null,
                    ''
                );
            }

            return [
                'title' => (string)($row['title'] ?? ''),
                'level' => (string)($row['level_label'] ?? ''),
                'chip_variant' => (string)($row['level_variant'] ?? 'neutral'),
                'skill' => (string)($row['skill'] ?? ''),
                'duration' => $duration !== '' ? $duration : '10 دقائق',
                'status' => (string)($row['status_label'] ?? ''),
                'status_variant' => (string)($row['status_variant'] ?? 'pending'),
            ];
        },
        $rows
    );
}

/**
 * @return array<int, array<string, string>>
 */
function getWeeklyPlanItems(): array
{
    $fallback = [
        [
            'title' => 'مراجعة الكانا',
            'description' => '15 دقيقة بطاقات تكرار',
            'status' => 'منجز',
            'status_variant' => 'done',
            'avatar' => 'ي',
            'avatar_variant' => 'pink',
        ],
        [
            'title' => 'درس المحادثة',
            'description' => 'تمرين نطق مع المعلم رينا',
            'status' => 'اليوم مساءً',
            'status_variant' => 'pending',
            'avatar' => 'م',
            'avatar_variant' => 'blue',
        ],
        [
            'title' => 'كلمات جديدة',
            'description' => '10 كلمات عن المواصلات',
            'status' => 'جارٍ',
            'status_variant' => 'in-progress',
            'avatar' => 'ك',
            'avatar_variant' => 'green',
        ],
    ];

    $rows = runQuery(
        'SELECT title, description, status_label, status_variant, avatar_label, avatar_variant
         FROM weekly_plan
         ORDER BY sort_order, id'
    );

    if (empty($rows)) {
        return $fallback;
    }

    return array_map(
        static fn (array $row): array => [
            'title' => (string)($row['title'] ?? ''),
            'description' => (string)($row['description'] ?? ''),
            'status' => (string)($row['status_label'] ?? ''),
            'status_variant' => (string)($row['status_variant'] ?? 'neutral'),
            'avatar' => (string)($row['avatar_label'] ?? ''),
            'avatar_variant' => (string)($row['avatar_variant'] ?? 'neutral'),
        ],
        $rows
    );
}

/**
 * @return array<int, array<string, string>>
 */
function getStudentNotifications(): array
{
    $fallback = [
        [
            'type' => 'success',
            'message' => 'تم فتح درس "التسوق في طوكيو" الآن.',
            'time_label' => 'قبل ساعة',
            'time_iso' => '2024-05-10T12:00:00',
        ],
        [
            'type' => 'warning',
            'message' => 'تذكير: اختبار مراجعة الهيراغانا غدًا صباحًا.',
            'time_label' => 'بعد 18 ساعة',
            'time_iso' => '2024-05-10T08:00:00',
        ],
        [
            'type' => 'info',
            'message' => 'انضممت إلى غرفة المحادثة الأسبوعية يوم الجمعة.',
            'time_label' => 'منذ يوم',
            'time_iso' => '2024-05-09T12:00:00',
        ],
    ];

    $rows = runQuery(
        'SELECT type_variant, message, time_label, time_iso
         FROM student_notifications
         ORDER BY sort_order, id'
    );

    if (empty($rows)) {
        return $fallback;
    }

    return array_map(
        static fn (array $row): array => [
            'type' => (string)($row['type_variant'] ?? 'info'),
            'message' => (string)($row['message'] ?? ''),
            'time_label' => (string)($row['time_label'] ?? ''),
            'time_iso' => (string)($row['time_iso'] ?? ''),
        ],
        $rows
    );
}

/**
 * @return array<int, array<string, int|string>>
 */
function getMonthlyProgress(): array
{
    $fallback = [
        ['label' => 'يناير', 'value' => 45],
        ['label' => 'فبراير', 'value' => 60],
        ['label' => 'مارس', 'value' => 75],
        ['label' => 'أبريل', 'value' => 85],
    ];

    $rows = runQuery(
        'SELECT label, progress_percent
         FROM monthly_progress
         ORDER BY sort_order, id'
    );

    if (empty($rows)) {
        return $fallback;
    }

    return array_map(
        static fn (array $row): array => [
            'label' => (string)($row['label'] ?? ''),
            'value' => isset($row['progress_percent']) ? (int) $row['progress_percent'] : 0,
        ],
        $rows
    );
}

/**
 * @return array<int, array<string, string>>
 */
function getTeacherMetrics(): array
{
    $fallback = [
        [
            'label' => 'عدد الطلاب المسجلين',
            'value' => '42 طالبًا',
            'trend' => '+5 هذا الشهر',
            'trend_type' => 'up',
        ],
        [
            'label' => 'دروس منشورة',
            'value' => '18 درسًا',
            'trend' => 'درس جديد قيد الإعداد',
            'trend_type' => 'neutral',
        ],
        [
            'label' => 'كويزات قيد المتابعة',
            'value' => '6 كويزات',
            'trend' => 'تم تقييم 2 مؤخرًا',
            'trend_type' => 'up',
        ],
        [
            'label' => 'تمارين تحتاج مراجعة',
            'value' => '4 تمارين',
            'trend' => '-3 عن الأسبوع الماضي',
            'trend_type' => 'down',
        ],
    ];

    $rows = runQuery(
        'SELECT label, value_text, trend_text, trend_type
         FROM teacher_metrics
         ORDER BY sort_order, id'
    );

    if (empty($rows)) {
        return $fallback;
    }

    return array_map(
        static fn (array $row): array => [
            'label' => (string)($row['label'] ?? ''),
            'value' => (string)($row['value_text'] ?? ''),
            'trend' => (string)($row['trend_text'] ?? ''),
            'trend_type' => (string)($row['trend_type'] ?? 'neutral'),
        ],
        $rows
    );
}

/**
 * @return array<int, array<string, string>>
 */
function getScheduledContent(): array
{
    $fallback = [
        [
            'title' => 'الدرس المباشر: مراجعة كانا',
            'type' => 'درس',
            'chip_variant' => 'design',
            'scheduled_at' => 'الاثنين 7 مساءً',
            'status' => 'تمت الجدولة',
            'status_variant' => 'in-progress',
        ],
        [
            'title' => 'كويز: الحروف الأساسية',
            'type' => 'كويز',
            'chip_variant' => 'dev',
            'scheduled_at' => 'الثلاثاء 5 مساءً',
            'status' => 'بانتظار النشر',
            'status_variant' => 'pending',
        ],
        [
            'title' => 'تمرين: تسجيل التحية',
            'type' => 'تمرين',
            'chip_variant' => 'qa',
            'scheduled_at' => 'الخميس 9 مساءً',
            'status' => 'مفعل',
            'status_variant' => 'done',
        ],
    ];

    $rows = runQuery(
        'SELECT title, type_label, type_variant, scheduled_at, status_label, status_variant
         FROM scheduled_content
         ORDER BY scheduled_at_datetime, id'
    );

    if (empty($rows)) {
        return $fallback;
    }

    return array_map(
        static fn (array $row): array => [
            'title' => (string)($row['title'] ?? ''),
            'type' => (string)($row['type_label'] ?? ''),
            'chip_variant' => (string)($row['type_variant'] ?? 'neutral'),
            'scheduled_at' => (string)($row['scheduled_at'] ?? ''),
            'status' => (string)($row['status_label'] ?? ''),
            'status_variant' => (string)($row['status_variant'] ?? 'pending'),
        ],
        $rows
    );
}

/**
 * @return array<int, array<string, string>>
 */
function getAdminMetrics(): array
{
    $fallback = [
        [
            'label' => 'الأعضاء النشطون',
            'value' => '128',
            'trend' => '+12 هذا الشهر',
            'trend_type' => 'up',
        ],
        [
            'label' => 'طلبات الانضمام المعلقة',
            'value' => '7',
            'trend' => 'تم قبول 3 اليوم',
            'trend_type' => 'neutral',
        ],
        [
            'label' => 'عدد الأدوار',
            'value' => '5 أدوار',
            'trend' => 'تمت إضافة دور جديد',
            'trend_type' => 'up',
        ],
        [
            'label' => 'تقارير الأنشطة',
            'value' => '18 تقريرًا',
            'trend' => '-4 عن الأسبوع الماضي',
            'trend_type' => 'down',
        ],
    ];

    $rows = runQuery(
        'SELECT label, value_text, trend_text, trend_type
         FROM admin_metrics
         ORDER BY sort_order, id'
    );

    if (empty($rows)) {
        return $fallback;
    }

    return array_map(
        static fn (array $row): array => [
            'label' => (string)($row['label'] ?? ''),
            'value' => (string)($row['value_text'] ?? ''),
            'trend' => (string)($row['trend_text'] ?? ''),
            'trend_type' => (string)($row['trend_type'] ?? 'neutral'),
        ],
        $rows
    );
}

/**
 * @return array<int, array<string, mixed>>
 */
function getTeamMembers(): array
{
    $fallback = [
        [
            'name' => 'ليلى الحربي',
            'email' => 'layla@iroha.co',
            'avatar' => 'ل',
            'avatar_variant' => 'pink',
            'role' => 'مسؤول فرعي',
            'role_variant' => 'accent',
            'status' => 'نشط',
            'status_variant' => 'done',
            'permissions' => 'إدارة المستخدمين، الموافقات، إعدادات الفوترة',
            'actions' => ['تعديل الدور', 'إيقاف مؤقت'],
        ],
        [
            'name' => 'سامي العتيبي',
            'email' => 'sami@iroha.co',
            'avatar' => 'س',
            'avatar_variant' => 'blue',
            'role' => 'مشرف محتوى',
            'role_variant' => 'info',
            'status' => 'بانتظار الموافقة',
            'status_variant' => 'in-progress',
            'permissions' => 'نشر الدروس، مراجعة التعليقات',
            'actions' => ['قبول الطلب', 'إعادة تعيين الدور'],
        ],
        [
            'name' => 'مها الياباني',
            'email' => 'maha@iroha.co',
            'avatar' => 'م',
            'avatar_variant' => 'green',
            'role' => 'معلمة',
            'role_variant' => 'success',
            'status' => 'نشط',
            'status_variant' => 'done',
            'permissions' => 'بث مباشر، إضافة تمارين، تقييم الواجبات',
            'actions' => ['تطوير الصلاحيات', 'إرسال رسالة'],
        ],
        [
            'name' => 'رامي السالم',
            'email' => 'rami@iroha.co',
            'avatar' => 'ر',
            'avatar_variant' => 'purple',
            'role' => 'طالب',
            'role_variant' => 'neutral',
            'status' => 'مقيّد',
            'status_variant' => 'pending',
            'permissions' => 'الوصول إلى الدروس المجدولة، المنتدى',
            'actions' => ['رفع التقييد', 'حذف الحساب'],
        ],
    ];

    $rows = runQuery(
        'SELECT name, email, avatar_label, avatar_variant, role_label, role_variant, status_label, status_variant, permissions_summary, actions
         FROM team_members
         ORDER BY sort_order, id'
    );

    if (empty($rows)) {
        return $fallback;
    }

    return array_map(
        static function (array $row): array {
            $actionsRaw = (string)($row['actions'] ?? '');
            $actions = array_values(
                array_filter(
                    array_map('trim', explode('|', $actionsRaw)),
                    static fn (string $action): bool => $action !== ''
                )
            );

            return [
                'name' => (string)($row['name'] ?? ''),
                'email' => (string)($row['email'] ?? ''),
                'avatar' => (string)($row['avatar_label'] ?? ''),
                'avatar_variant' => (string)($row['avatar_variant'] ?? 'neutral'),
                'role' => (string)($row['role_label'] ?? ''),
                'role_variant' => (string)($row['role_variant'] ?? 'neutral'),
                'status' => (string)($row['status_label'] ?? ''),
                'status_variant' => (string)($row['status_variant'] ?? 'pending'),
                'permissions' => (string)($row['permissions_summary'] ?? ''),
                'actions' => $actions,
            ];
        },
        $rows
    );
}

/**
 * @return array<int, array<string, mixed>>
 */
function getRoleDefinitions(): array
{
    $fallback = [
        [
            'title' => 'مسؤول رئيسي',
            'badge_variant' => 'accent',
            'badge_label' => 'وصول كامل',
            'features' => [
                'إدارة إعدادات المنصة العامة',
                'إضافة وحذف مستخدمين',
                'الوصول للتقارير الحساسة',
            ],
        ],
        [
            'title' => 'مشرف محتوى',
            'badge_variant' => 'info',
            'badge_label' => 'تحكم متوسط',
            'features' => [
                'نشر وتعديل الدروس',
                'إدارة ملفات الوسائط',
                'مراجعة تعليقات الطلاب',
            ],
        ],
        [
            'title' => 'الدعم الفني',
            'badge_variant' => 'neutral',
            'badge_label' => 'صلاحيات محدودة',
            'features' => [
                'الرد على تذاكر الدعم',
                'إرسال تنبيهات النظام',
                'إدارة مركز المساعدة',
            ],
        ],
    ];

    $rows = runQuery(
        'SELECT title, badge_variant, badge_label, feature_list
         FROM role_definitions
         ORDER BY sort_order, id'
    );

    if (empty($rows)) {
        return $fallback;
    }

    return array_map(
        static function (array $row): array {
            $featuresRaw = (string)($row['feature_list'] ?? '');
            $features = array_values(
                array_filter(
                    array_map('trim', preg_split('/\r?\n|,/', $featuresRaw) ?: []),
                    static fn (string $feature): bool => $feature !== ''
                )
            );

            return [
                'title' => (string)($row['title'] ?? ''),
                'badge_variant' => (string)($row['badge_variant'] ?? 'neutral'),
                'badge_label' => (string)($row['badge_label'] ?? ''),
                'features' => $features,
            ];
        },
        $rows
    );
}

/**
 * @return array<int, array<string, mixed>>
 */
function getPermissionGroups(): array
{
    $fallback = [
        [
            'title' => 'إدارة المحتوى',
            'description' => 'يتحكم في عمليات إنشاء ونشر المواد التعليمية.',
            'permissions' => [
                ['label' => 'نشر الدروس', 'active' => true],
                ['label' => 'تعديل الدروس المنشورة', 'active' => true],
                ['label' => 'حذف المحتوى نهائيًا', 'active' => false],
            ],
        ],
        [
            'title' => 'إدارة المستخدمين',
            'description' => 'إجراءات الموافقة، الإيقاف، ومنح الصلاحيات.',
            'permissions' => [
                ['label' => 'قبول طلبات التسجيل', 'active' => true],
                ['label' => 'إعادة تعيين كلمة المرور', 'active' => false],
                ['label' => 'تفعيل المصادقة المتعددة', 'active' => false],
            ],
        ],
        [
            'title' => 'إعدادات الدفع',
            'description' => 'التحكم بالاشتراكات والخطط المالية.',
            'permissions' => [
                ['label' => 'تحديث الأسعار', 'active' => false],
                ['label' => 'عرض تقارير الإيرادات', 'active' => true],
                ['label' => 'إدارة بطاقات الدفع المخزنة', 'active' => false],
            ],
        ],
    ];

    $groups = runQuery(
        'SELECT id, title, description
         FROM permission_groups
         ORDER BY sort_order, id'
    );

    if (empty($groups)) {
        return $fallback;
    }

    $groupIds = array_column($groups, 'id');
    $placeholders = implode(',', array_fill(0, count($groupIds), '?'));
    $items = runQuery(
        'SELECT group_id, label, is_active
         FROM permission_items
         WHERE group_id IN (' . $placeholders . ')
         ORDER BY sort_order, id',
        $groupIds
    );

    if ($items === null) {
        return $fallback;
    }

    $permissionsByGroup = [];
    foreach ($items as $item) {
        $groupId = isset($item['group_id']) ? (int) $item['group_id'] : 0;
        if ($groupId <= 0) {
            continue;
        }

        $permissionsByGroup[$groupId][] = [
            'label' => (string)($item['label'] ?? ''),
            'active' => isset($item['is_active']) && (int) $item['is_active'] === 1,
        ];
    }

    $result = [];
    foreach ($groups as $group) {
        $groupId = isset($group['id']) ? (int) $group['id'] : 0;
        $result[] = [
            'title' => (string)($group['title'] ?? ''),
            'description' => (string)($group['description'] ?? ''),
            'permissions' => $permissionsByGroup[$groupId] ?? [],
        ];
    }

    if (empty($result)) {
        return $fallback;
    }

    return $result;
}
