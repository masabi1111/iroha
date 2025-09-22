<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/data-providers.php';

if (!function_exists('e')) {
    function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

$metrics = getAdminMetrics();
$members = getTeamMembers();
$roles = getRoleDefinitions();
$permissionGroups = getPermissionGroups();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>لوحة المسؤول - إروها</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="assets/css/styles.css" />
  </head>
  <body class="dashboard dashboard--admin">
    <aside class="sidebar" aria-label="قائمة جانبية للمسؤول">
      <div class="sidebar__brand">إروها</div>
      <button class="sidebar__toggle" type="button" aria-label="إخفاء القائمة">☰</button>
      <nav class="sidebar__nav" aria-label="روابط لوحة المسؤول">
        <a class="is-active" href="#admin-overview">نظرة عامة</a>
        <a href="#member-management">إدارة الأعضاء</a>
        <a href="#role-management">الأدوار والفرق</a>
        <a href="#permission-policies">سياسات الصلاحيات</a>
      </nav>
      <div class="sidebar__footer">
        <a class="btn btn--outline" href="index.html">العودة للرئيسية</a>
      </div>
    </aside>

    <div class="dashboard__main">
      <header class="dashboard__header">
        <div>
          <h1>مرحبًا، أستاذ خالد</h1>
          <p>
            يمكنك إدارة الأعضاء، ضبط الصلاحيات، ومراقبة التغييرات في فريق العمل بكل
            سهولة.
          </p>
        </div>
        <div class="dashboard__actions">
          <button class="btn" type="button">دعوة عضو جديد</button>
          <button class="btn btn--ghost" type="button">إعدادات عامة</button>
        </div>
      </header>

      <section class="cards" id="admin-overview" aria-label="مؤشرات عامة">
        <?php foreach ($metrics as $metric): ?>
          <?php $trendType = trim((string)($metric['trend_type'] ?? 'neutral')); ?>
          <article class="card card--metric">
            <span class="card__label"><?= e($metric['label'] ?? '') ?></span>
            <strong class="card__value"><?= e($metric['value'] ?? '') ?></strong>
            <?php if (!empty($metric['trend'])): ?>
              <span class="card__trend <?= $trendType !== '' ? e($trendType) : 'neutral' ?>">
                <?= e($metric['trend']) ?>
              </span>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      </section>

      <section class="card card--panel" id="member-management" aria-labelledby="member-management-title">
        <div class="card__header">
          <div>
            <h2 id="member-management-title">إدارة الأعضاء</h2>
            <p class="card__subtitle">
              راقب حالة الحسابات بسرعة، قم بتعديل الأدوار، أو أرسل دعوات جديدة.
            </p>
          </div>
          <div class="member-filters">
            <label class="input-icon">
              <span aria-hidden="true">🔍</span>
              <input type="search" placeholder="البحث باسم العضو أو بريده" />
            </label>
            <select aria-label="تصفية حسب الحالة">
              <option>جميع الحالات</option>
              <option>نشط</option>
              <option>بانتظار التفعيل</option>
              <option>مقيّد</option>
            </select>
            <select aria-label="تصفية حسب الدور">
              <option>جميع الأدوار</option>
              <option>مسؤول</option>
              <option>مشرف محتوى</option>
              <option>معلم</option>
              <option>طالب</option>
            </select>
            <button class="btn btn--ghost btn--small" type="button">تصدير القائمة</button>
          </div>
        </div>
        <div class="table-wrapper" role="region" aria-live="polite">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">العضو</th>
                <th scope="col">الدور الحالي</th>
                <th scope="col">الحالة</th>
                <th scope="col">أبرز الصلاحيات</th>
                <th scope="col">إجراءات</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($members as $member): ?>
                <?php
                  $avatarVariant = trim((string)($member['avatar_variant'] ?? ''));
                  $roleVariant = trim((string)($member['role_variant'] ?? ''));
                  $statusVariant = trim((string)($member['status_variant'] ?? ''));
                  $actions = $member['actions'] ?? [];
                ?>
                <tr>
                  <td>
                    <div class="member-cell">
                      <span class="avatar<?= $avatarVariant !== '' ? ' avatar--' . e($avatarVariant) : '' ?>">
                        <?= e($member['avatar'] ?? '') ?>
                      </span>
                      <div>
                        <strong><?= e($member['name'] ?? '') ?></strong>
                        <span class="member-email"><?= e($member['email'] ?? '') ?></span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="badge<?= $roleVariant !== '' ? ' badge--' . e($roleVariant) : '' ?>">
                      <?= e($member['role'] ?? '') ?>
                    </span>
                  </td>
                  <td>
                    <span class="status<?= $statusVariant !== '' ? ' status--' . e($statusVariant) : '' ?>">
                      <?= e($member['status'] ?? '') ?>
                    </span>
                  </td>
                  <td><?= e($member['permissions'] ?? '') ?></td>
                  <td>
                    <div class="member-actions">
                      <?php foreach ($actions as $action): ?>
                        <button class="btn btn--ghost btn--small" type="button"><?= e($action) ?></button>
                      <?php endforeach; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>

      <section class="card card--panel" id="role-management" aria-labelledby="role-management-title">
        <div class="card__header">
          <div>
            <h2 id="role-management-title">الأدوار والفرق</h2>
            <p class="card__subtitle">
              خصصي الأدوار حسب احتياج فرق العمل واضبطي الصلاحيات الافتراضية لكل دور.
            </p>
          </div>
          <button class="btn btn--ghost btn--small" type="button">إضافة دور مخصص</button>
        </div>
        <div class="role-cards" role="list">
          <?php foreach ($roles as $role): ?>
            <?php $badgeVariant = trim((string)($role['badge_variant'] ?? '')); ?>
            <article class="role-card" role="listitem">
              <header>
                <h3><?= e($role['title'] ?? '') ?></h3>
                <span class="badge<?= $badgeVariant !== '' ? ' badge--' . e($badgeVariant) : '' ?>">
                  <?= e($role['badge_label'] ?? '') ?>
                </span>
              </header>
              <ul>
                <?php foreach ($role['features'] ?? [] as $feature): ?>
                  <li><?= e((string) $feature) ?></li>
                <?php endforeach; ?>
              </ul>
              <button class="btn btn--ghost btn--small" type="button">تعيين كدور افتراضي</button>
            </article>
          <?php endforeach; ?>
        </div>
      </section>

      <section class="card card--panel" id="permission-policies" aria-labelledby="permission-policies-title">
        <div class="card__header">
          <div>
            <h2 id="permission-policies-title">سياسات الصلاحيات التفصيلية</h2>
            <p class="card__subtitle">
              فعّلي أو عطّلي الصلاحيات بسرعة عبر الجداول التالية، ويتم تطبيقها مباشرةً على الأدوار المرتبطة.
            </p>
          </div>
          <button class="btn btn--ghost btn--small" type="button">حفظ التعديلات</button>
        </div>
        <div class="permissions-grid">
          <?php foreach ($permissionGroups as $group): ?>
            <article class="permissions-group">
              <header>
                <h3><?= e($group['title'] ?? '') ?></h3>
                <p><?= e($group['description'] ?? '') ?></p>
              </header>
              <div class="permissions-list">
                <?php foreach ($group['permissions'] ?? [] as $permission): ?>
                  <?php $isActive = !empty($permission['active']); ?>
                  <button
                    class="permission-toggle<?= $isActive ? ' is-active' : '' ?>"
                    type="button"
                    data-permission-toggle
                    aria-pressed="<?= $isActive ? 'true' : 'false' ?>"
                  ><?= e((string)($permission['label'] ?? '')) ?></button>
                <?php endforeach; ?>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </section>
    </div>

    <script src="assets/js/main.js"></script>
  </body>
</html>
