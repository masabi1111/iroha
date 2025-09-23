<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/data-providers.php';

if (!function_exists('e')) {
    function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

$metrics = getTeacherMetrics();
$scheduledItems = getScheduledContent();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>لوحة المعلم - إروها</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="assets/css/styles.css" />
  </head>
  <body class="dashboard dashboard--teacher">
    <aside class="sidebar" aria-label="قائمة جانبية للمعلم">
      <div class="sidebar__brand">إروها</div>
      <button class="sidebar__toggle" aria-label="إخفاء القائمة">☰</button>
      <nav class="sidebar__nav" aria-label="روابط لوحة المعلم">
        <a class="is-active" href="#teacher-overview">نظرة عامة</a>
        <a href="#quick-actions">إجراءات سريعة</a>
        <a href="#create-content">إنشاء محتوى</a>
        <a href="#scheduled-items">المحتوى المجدول</a>
      </nav>
      <div class="sidebar__footer">
        <a class="btn btn--outline" href="index.html">العودة للرئيسية</a>
      </div>
    </aside>

    <div class="dashboard__main">
      <header class="dashboard__header">
        <div>
          <h1>مرحبًا، أستاذة نورة</h1>
          <p>
            يمكنك إدارة دروسك وواجباتك من مكان واحد، ومتابعة ما تم نشره وما يزال
            قيد الإعداد.
          </p>
        </div>
        <div class="dashboard__actions">
          <button class="btn btn--ghost" type="button">مراجعة طلبات الطلاب</button>
          <button class="btn" type="button">إنشاء درس مباشر</button>
        </div>
      </header>

      <section class="cards" id="teacher-overview" aria-label="إحصائيات عامة">
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

      <section class="card card--panel" id="quick-actions" aria-labelledby="quick-actions-title">
        <div class="card__header">
          <h2 id="quick-actions-title">إجراءات سريعة</h2>
          <p class="card__subtitle">اختصري وقتك بالانتقال مباشرة إلى المهام الأكثر تكرارًا.</p>
        </div>
        <div class="quick-actions" role="list">
          <a class="quick-actions__action" href="#lesson-form" role="listitem">
            <span class="quick-actions__icon" aria-hidden="true">📘</span>
            <strong>إضافة درس جديد</strong>
            <p>تحديد الأهداف، الموارد، وخطة التقييم.</p>
          </a>
          <a class="quick-actions__action" href="#quiz-form" role="listitem">
            <span class="quick-actions__icon" aria-hidden="true">📝</span>
            <strong>إنشاء كويز</strong>
            <p>ربط الكويز بالدرس المناسب وجدولة فتحه.</p>
          </a>
          <a class="quick-actions__action" href="#exercise-form" role="listitem">
            <span class="quick-actions__icon" aria-hidden="true">🎯</span>
            <strong>إضافة تمرين تطبيقي</strong>
            <p>قدمي نشاطًا عمليًا مع موارد داعمة.</p>
          </a>
        </div>
      </section>

      <section class="content-forms" id="create-content" aria-label="نماذج إنشاء المحتوى">
        <article class="card card--panel content-form-card" id="lesson-form" aria-labelledby="lesson-form-title">
          <div class="card__header">
            <h2 id="lesson-form-title">إضافة درس جديد</h2>
            <p class="card__subtitle">املئي الحقول الأساسية قبل نشر الدرس للطلاب.</p>
          </div>
          <form class="content-form">
            <div class="form-grid">
              <div class="form-group">
                <label for="lesson-title">عنوان الدرس</label>
                <input
                  type="text"
                  id="lesson-title"
                  name="lesson-title"
                  placeholder="مثال: مقدمة الهيراغانا"
                  required
                />
              </div>
              <div class="form-group">
                <label for="lesson-level">المستوى</label>
                <select id="lesson-level" name="lesson-level">
                  <option>مبتدئ</option>
                  <option>ما قبل المتوسط</option>
                  <option>متوسط</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="lesson-objectives">أهداف الدرس</label>
              <textarea
                id="lesson-objectives"
                name="lesson-objectives"
                rows="4"
                placeholder="حددي ثلاث نقاط رئيسية يتعلمها الطالب في هذا الدرس."
              ></textarea>
            </div>
            <div class="form-grid">
              <div class="form-group">
                <label for="lesson-duration">المدة المتوقعة (دقائق)</label>
                <input
                  type="number"
                  id="lesson-duration"
                  name="lesson-duration"
                  min="5"
                  placeholder="30"
                />
              </div>
              <div class="form-group">
                <label for="lesson-release">تاريخ النشر</label>
                <input type="date" id="lesson-release" name="lesson-release" />
              </div>
            </div>
            <div class="form-group">
              <label for="lesson-resources">روابط أو ملفات مساندة</label>
              <input
                type="url"
                id="lesson-resources"
                name="lesson-resources"
                placeholder="أضيفي رابط عرض تقديمي أو مستند داعم"
              />
              <small class="form-hint">يمكن إضافة روابط متعددة لاحقًا من صفحة تفاصيل الدرس.</small>
            </div>
            <div class="form-actions">
              <button class="btn" type="submit">حفظ الدرس</button>
              <button class="btn btn--ghost" type="reset">مسح الحقول</button>
            </div>
          </form>
        </article>

        <article class="card card--panel content-form-card" id="quiz-form" aria-labelledby="quiz-form-title">
          <div class="card__header">
            <h2 id="quiz-form-title">إنشاء كويز تقييمي</h2>
            <p class="card__subtitle">اختاري الدرس المستهدف وحددي إعدادات الكويز الأساسية.</p>
          </div>
          <form class="content-form">
            <div class="form-grid">
              <div class="form-group">
                <label for="quiz-title">عنوان الكويز</label>
                <input
                  type="text"
                  id="quiz-title"
                  name="quiz-title"
                  placeholder="مثال: اختبار الهيراغانا السريع"
                  required
                />
              </div>
              <div class="form-group">
                <label for="quiz-lesson">مرتبط بالدرس</label>
                <select id="quiz-lesson" name="quiz-lesson">
                  <option>مقدمة الهيراغانا</option>
                  <option>كتابة الحروف الأساسية</option>
                  <option>تحية الصباح</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="quiz-description">تعليمات للطلاب</label>
              <textarea
                id="quiz-description"
                name="quiz-description"
                rows="4"
                placeholder="وضحي عدد المحاولات المتاحة ومعايير النجاح."
              ></textarea>
            </div>
            <div class="form-grid">
              <div class="form-group">
                <label for="quiz-question-count">عدد الأسئلة</label>
                <input
                  type="number"
                  id="quiz-question-count"
                  name="quiz-question-count"
                  min="1"
                  placeholder="10"
                />
              </div>
              <div class="form-group">
                <label for="quiz-availability">وقت الإتاحة</label>
                <input
                  type="datetime-local"
                  id="quiz-availability"
                  name="quiz-availability"
                />
              </div>
            </div>
            <div class="form-group">
              <label for="quiz-tags">وسوم تنظيمية</label>
              <input
                type="text"
                id="quiz-tags"
                name="quiz-tags"
                placeholder="مثال: مراجعة، حروف، أسبوع-3"
              />
              <small class="form-hint">افصلي بين الوسوم باستخدام فاصلة عربية أو إنجليزية.</small>
            </div>
            <div class="form-actions">
              <button class="btn" type="submit">حفظ الكويز</button>
              <button class="btn btn--ghost" type="reset">مسح الحقول</button>
            </div>
          </form>
        </article>

        <article class="card card--panel content-form-card" id="exercise-form" aria-labelledby="exercise-form-title">
          <div class="card__header">
            <h2 id="exercise-form-title">إضافة تمرين تطبيقي</h2>
            <p class="card__subtitle">شجعي الطلاب على التطبيق العملي مع إرشادات واضحة.</p>
          </div>
          <form class="content-form">
            <div class="form-grid">
              <div class="form-group">
                <label for="exercise-title">عنوان التمرين</label>
                <input
                  type="text"
                  id="exercise-title"
                  name="exercise-title"
                  placeholder="مثال: تسجيل نطق كلمات التحية"
                  required
                />
              </div>
              <div class="form-group">
                <label for="exercise-skill">المهارة المستهدفة</label>
                <select id="exercise-skill" name="exercise-skill">
                  <option>الاستماع</option>
                  <option>المحادثة</option>
                  <option>القراءة</option>
                  <option>الكتابة</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="exercise-instructions">التعليمات</label>
              <textarea
                id="exercise-instructions"
                name="exercise-instructions"
                rows="4"
                placeholder="اشرحي خطوات تنفيذ التمرين ومتطلبات التسليم."
              ></textarea>
            </div>
            <div class="form-grid">
              <div class="form-group">
                <label for="exercise-due">آخر موعد للتسليم</label>
                <input type="datetime-local" id="exercise-due" name="exercise-due" />
              </div>
              <div class="form-group">
                <label for="exercise-format">نوع التسليم</label>
                <select id="exercise-format" name="exercise-format">
                  <option>رفع ملف صوتي</option>
                  <option>إجابة كتابية</option>
                  <option>رابط فيديو</option>
                  <option>تمرين تفاعلي</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="exercise-feedback">معايير التقييم</label>
              <textarea
                id="exercise-feedback"
                name="exercise-feedback"
                rows="3"
                placeholder="حددي عناصر التقييم مثل النطق، الدقة، الطلاقة."
              ></textarea>
            </div>
            <div class="form-actions">
              <button class="btn" type="submit">حفظ التمرين</button>
              <button class="btn btn--ghost" type="reset">مسح الحقول</button>
            </div>
          </form>
        </article>
      </section>

      <section class="card card--panel" id="scheduled-items" aria-labelledby="scheduled-items-title">
        <div class="card__header">
          <h2 id="scheduled-items-title">المحتوى المجدول للأسبوع</h2>
          <button class="btn btn--ghost" type="button">تعديل الجدول</button>
        </div>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">العنصر</th>
              <th scope="col">النوع</th>
              <th scope="col">التاريخ</th>
              <th scope="col">الحالة</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($scheduledItems as $item): ?>
              <?php
                $chipVariant = trim((string)($item['chip_variant'] ?? ''));
                $statusVariant = trim((string)($item['status_variant'] ?? ''));
              ?>
              <tr>
                <td><?= e($item['title'] ?? '') ?></td>
                <td>
                  <span class="chip<?= $chipVariant !== '' ? ' chip--' . e($chipVariant) : '' ?>">
                    <?= e($item['type'] ?? '') ?>
                  </span>
                </td>
                <td><?= e($item['scheduled_at'] ?? '') ?></td>
                <td>
                  <span class="status<?= $statusVariant !== '' ? ' status--' . e($statusVariant) : '' ?>">
                    <?= e($item['status'] ?? '') ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </section>
    </div>

    <script src="assets/js/main.js" defer></script>
  </body>
</html>
