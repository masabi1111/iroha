<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/data-providers.php';

if (!function_exists('e')) {
    function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

$metrics = getDashboardMetrics();
$lessons = getDashboardLessons();
$weeklyPlanItems = getWeeklyPlanItems();
$notifications = getStudentNotifications();
$progressSeries = getMonthlyProgress();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>لوحة التعلم - إروها</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="assets/css/styles.css" />
  </head>
  <body class="dashboard">
    <aside class="sidebar" aria-label="قائمة جانبية">
      <div class="sidebar__brand">إروها</div>
      <button class="sidebar__toggle" aria-label="إخفاء القائمة">
        ☰
      </button>
      <nav class="sidebar__nav" aria-label="القائمة الرئيسية">
        <a class="is-active" href="#overview">نظرة عامة</a>
        <a href="#tasks">الدروس</a>
        <a href="#team">خطة الأسبوع</a>
        <a href="#reports">التقدم</a>
        <a href="#settings">الإشعارات</a>
      </nav>
      <div class="sidebar__footer">
        <a class="btn btn--outline" href="index.html">العودة للرئيسية</a>
      </div>
    </aside>

    <div class="dashboard__main">
      <header class="dashboard__header">
        <div>
          <h1>مرحبًا، ليان</h1>
          <p>تابع تقدمك في مستوى المبتدئين وتعرف على الدروس المقترحة لك اليوم.</p>
        </div>
        <div class="dashboard__actions">
          <button class="btn btn--ghost" type="button">متابعة الدرس الحالي</button>
          <button class="btn" type="button">جدولة مراجعة</button>
        </div>
      </header>

      <section class="cards" id="overview" aria-label="مؤشرات أساسية">
        <?php foreach ($metrics as $metric): ?>
          <article class="card card--metric">
            <span class="card__label"><?= e($metric['label'] ?? '') ?></span>
            <strong class="card__value"><?= e($metric['value'] ?? '') ?></strong>
            <?php if (!empty($metric['trend'])): ?>
              <?php $trendType = trim((string)($metric['trend_type'] ?? 'neutral')); ?>
              <span class="card__trend <?= $trendType !== '' ? e($trendType) : 'neutral' ?>">
                <?= e($metric['trend']) ?>
              </span>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      </section>

      <section class="card card--panel" id="tasks" aria-labelledby="tasks-title">
        <div class="card__header">
          <h2 id="tasks-title">آخر الدروس والتمارين</h2>
          <div class="card__filters">
            <select>
              <option>الدروس النشطة</option>
              <option>الدروس المكتملة</option>
              <option>الدروس القادمة</option>
            </select>
            <button class="btn btn--ghost" type="button">تصدير التقدم</button>
          </div>
        </div>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">الدرس</th>
              <th scope="col">المستوى</th>
              <th scope="col">المهارة</th>
              <th scope="col">المدة</th>
              <th scope="col">الحالة</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($lessons as $lesson): ?>
              <?php
                $chipVariant = trim((string)($lesson['chip_variant'] ?? ''));
                $statusVariant = trim((string)($lesson['status_variant'] ?? ''));
              ?>
              <tr>
                <td><?= e($lesson['title'] ?? '') ?></td>
                <td>
                  <span class="chip<?= $chipVariant !== '' ? ' chip--' . e($chipVariant) : '' ?>">
                    <?= e($lesson['level'] ?? '') ?>
                  </span>
                </td>
                <td><?= e($lesson['skill'] ?? '') ?></td>
                <td><?= e($lesson['duration'] ?? '') ?></td>
                <td>
                  <span class="status<?= $statusVariant !== '' ? ' status--' . e($statusVariant) : '' ?>">
                    <?= e($lesson['status'] ?? '') ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </section>

      <section class="dashboard__grid">
        <article class="card card--panel" id="team" aria-labelledby="team-title">
          <div class="card__header">
            <h2 id="team-title">خطة الأسبوع الحالي</h2>
            <button class="btn btn--ghost" type="button">تعديل الخطة</button>
          </div>
          <ul class="team-list">
            <?php foreach ($weeklyPlanItems as $item): ?>
              <?php $statusVariant = trim((string)($item['status_variant'] ?? '')); ?>
              <li>
                <span class="avatar<?= !empty($item['avatar_variant']) ? ' avatar--' . e($item['avatar_variant']) : '' ?>">
                  <?= e($item['avatar'] ?? '') ?>
                </span>
                <div>
                  <strong><?= e($item['title'] ?? '') ?></strong>
                  <span><?= e($item['description'] ?? '') ?></span>
                </div>
                <span class="status<?= $statusVariant !== '' ? ' status--' . e($statusVariant) : '' ?>">
                  <?= e($item['status'] ?? '') ?>
                </span>
              </li>
            <?php endforeach; ?>
          </ul>
        </article>

        <article class="card card--panel" id="reports" aria-labelledby="reports-title">
          <div class="card__header">
            <h2 id="reports-title">منحنى التقدم الشهري</h2>
            <button class="btn btn--ghost" type="button">تنزيل التقرير</button>
          </div>
          <div class="chart-placeholder" role="img" aria-label="مخطط بياني افتراضي">
            <div class="chart__bars">
              <?php foreach ($progressSeries as $point): ?>
                <?php $height = max(0, min(100, (int)($point['value'] ?? 0))); ?>
                <span style="height: <?= $height ?>%"></span>
              <?php endforeach; ?>
            </div>
            <div class="chart__labels">
              <?php foreach ($progressSeries as $point): ?>
                <span><?= e((string)($point['label'] ?? '')) ?></span>
              <?php endforeach; ?>
            </div>
          </div>
        </article>

        <article class="card card--panel" id="settings" aria-labelledby="settings-title">
          <div class="card__header">
            <h2 id="settings-title">آخر التنبيهات</h2>
            <button class="btn btn--ghost" type="button">إدارة التنبيهات</button>
          </div>
          <ul class="notifications">
            <?php foreach ($notifications as $notification): ?>
              <?php
                $dotVariant = trim((string)($notification['type'] ?? 'info'));
                $timeIso = trim((string)($notification['time_iso'] ?? ''));
                $timeAttr = $timeIso !== '' ? ' datetime="' . e($timeIso) . '"' : '';
              ?>
              <li>
                <span class="dot dot--<?= $dotVariant !== '' ? e($dotVariant) : 'info' ?>"></span>
                <?= e($notification['message'] ?? '') ?>
                <?php if (!empty($notification['time_label'])): ?>
                  <time<?= $timeAttr ?>><?= e($notification['time_label']) ?></time>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </article>
      </section>
    </div>

    <script src="assets/js/main.js" defer></script>
  </body>
</html>
