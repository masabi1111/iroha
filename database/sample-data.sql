-- بيانات توضيحية يمكن استخدامها لملء الجداول بعد إنشاء المخطط
INSERT INTO dashboard_metrics (label, value_text, trend_text, trend_type, sort_order) VALUES
  ('التقدم العام', '52%', '+6% هذا الأسبوع', 'up', 1),
  ('حروف الهيراغانا المتقنة', '38/46', 'تمت إضافة 4 حروف جديدة', 'up', 2),
  ('المفردات الجديدة', '24 كلمة', 'ثابتة', 'neutral', 3),
  ('أيام الممارسة المتتالية', '7 أيام', 'حافظ على السلسلة!', 'up', 4);

INSERT INTO student_lessons (title, level_label, level_variant, skill, duration_text, status_label, status_variant, sort_order) VALUES
  ('مقدمة الهيراغانا', 'أساسيات', 'design', 'التعرف على الحروف あ い う', '12 دقيقة', 'مكتمل', 'done', 1),
  ('تحية الصباح والمساء', 'محادثة', 'dev', 'جمل はじめまして و おはよう', '18 دقيقة', 'قيد الدراسة', 'in-progress', 2),
  ('الكانا في الكلمات', 'مفردات', 'analytics', 'ربط الحروف بكلمات يومية', '15 دقيقة', 'للمراجعة', 'pending', 3),
  ('أرقام حتى 20', 'قواعد', 'brand', 'أنماط العد اليابانية', '10 دقائق', 'قيد التقدم', 'in-progress', 4);

INSERT INTO weekly_plan (title, description, status_label, status_variant, avatar_label, avatar_variant, sort_order) VALUES
  ('مراجعة الكانا', '15 دقيقة بطاقات تكرار', 'منجز', 'done', 'ي', 'pink', 1),
  ('درس المحادثة', 'تمرين نطق مع المعلم رينا', 'اليوم مساءً', 'pending', 'م', 'blue', 2),
  ('كلمات جديدة', '10 كلمات عن المواصلات', 'جارٍ', 'in-progress', 'ك', 'green', 3);

INSERT INTO student_notifications (type_variant, message, time_label, time_iso, sort_order) VALUES
  ('success', 'تم فتح درس "التسوق في طوكيو" الآن.', 'قبل ساعة', '2024-05-10T12:00:00', 1),
  ('warning', 'تذكير: اختبار مراجعة الهيراغانا غدًا صباحًا.', 'بعد 18 ساعة', '2024-05-10T08:00:00', 2),
  ('info', 'انضممت إلى غرفة المحادثة الأسبوعية يوم الجمعة.', 'منذ يوم', '2024-05-09T12:00:00', 3);

INSERT INTO monthly_progress (label, progress_percent, sort_order) VALUES
  ('يناير', 45, 1),
  ('فبراير', 60, 2),
  ('مارس', 75, 3),
  ('أبريل', 85, 4);

INSERT INTO teacher_metrics (label, value_text, trend_text, trend_type, sort_order) VALUES
  ('عدد الطلاب المسجلين', '42 طالبًا', '+5 هذا الشهر', 'up', 1),
  ('دروس منشورة', '18 درسًا', 'درس جديد قيد الإعداد', 'neutral', 2),
  ('كويزات قيد المتابعة', '6 كويزات', 'تم تقييم 2 مؤخرًا', 'up', 3),
  ('تمارين تحتاج مراجعة', '4 تمارين', '-3 عن الأسبوع الماضي', 'down', 4);

INSERT INTO scheduled_content (title, type_label, type_variant, scheduled_at, scheduled_at_datetime, status_label, status_variant) VALUES
  ('الدرس المباشر: مراجعة كانا', 'درس', 'design', 'الاثنين 7 مساءً', '2024-05-13 19:00:00', 'تمت الجدولة', 'in-progress'),
  ('كويز: الحروف الأساسية', 'كويز', 'dev', 'الثلاثاء 5 مساءً', '2024-05-14 17:00:00', 'بانتظار النشر', 'pending'),
  ('تمرين: تسجيل التحية', 'تمرين', 'qa', 'الخميس 9 مساءً', '2024-05-16 21:00:00', 'مفعل', 'done');

INSERT INTO admin_metrics (label, value_text, trend_text, trend_type, sort_order) VALUES
  ('الأعضاء النشطون', '128', '+12 هذا الشهر', 'up', 1),
  ('طلبات الانضمام المعلقة', '7', 'تم قبول 3 اليوم', 'neutral', 2),
  ('عدد الأدوار', '5 أدوار', 'تمت إضافة دور جديد', 'up', 3),
  ('تقارير الأنشطة', '18 تقريرًا', '-4 عن الأسبوع الماضي', 'down', 4);

INSERT INTO team_members (name, email, avatar_label, avatar_variant, role_label, role_variant, status_label, status_variant, permissions_summary, actions, sort_order) VALUES
  ('ليلى الحربي', 'layla@iroha.co', 'ل', 'pink', 'مسؤول فرعي', 'accent', 'نشط', 'done', 'إدارة المستخدمين، الموافقات، إعدادات الفوترة', 'تعديل الدور|إيقاف مؤقت', 1),
  ('سامي العتيبي', 'sami@iroha.co', 'س', 'blue', 'مشرف محتوى', 'info', 'بانتظار الموافقة', 'in-progress', 'نشر الدروس، مراجعة التعليقات', 'قبول الطلب|إعادة تعيين الدور', 2),
  ('مها الياباني', 'maha@iroha.co', 'م', 'green', 'معلمة', 'success', 'نشط', 'done', 'بث مباشر، إضافة تمارين، تقييم الواجبات', 'تطوير الصلاحيات|إرسال رسالة', 3),
  ('رامي السالم', 'rami@iroha.co', 'ر', 'purple', 'طالب', 'neutral', 'مقيّد', 'pending', 'الوصول إلى الدروس المجدولة، المنتدى', 'رفع التقييد|حذف الحساب', 4);

INSERT INTO role_definitions (title, badge_variant, badge_label, feature_list, sort_order) VALUES
  ('مسؤول رئيسي', 'accent', 'وصول كامل', 'إدارة إعدادات المنصة العامة\nإضافة وحذف مستخدمين\nالوصول للتقارير الحساسة', 1),
  ('مشرف محتوى', 'info', 'تحكم متوسط', 'نشر وتعديل الدروس\nإدارة ملفات الوسائط\nمراجعة تعليقات الطلاب', 2),
  ('الدعم الفني', 'neutral', 'صلاحيات محدودة', 'الرد على تذاكر الدعم\nإرسال تنبيهات النظام\nإدارة مركز المساعدة', 3);

INSERT INTO permission_groups (title, description, sort_order) VALUES
  ('إدارة المحتوى', 'يتحكم في عمليات إنشاء ونشر المواد التعليمية.', 1),
  ('إدارة المستخدمين', 'إجراءات الموافقة، الإيقاف، ومنح الصلاحيات.', 2),
  ('إعدادات الدفع', 'التحكم بالاشتراكات والخطط المالية.', 3);

INSERT INTO permission_items (group_id, label, is_active, sort_order) VALUES
  (1, 'نشر الدروس', 1, 1),
  (1, 'تعديل الدروس المنشورة', 1, 2),
  (1, 'حذف المحتوى نهائيًا', 0, 3),
  (2, 'قبول طلبات التسجيل', 1, 1),
  (2, 'إعادة تعيين كلمة المرور', 0, 2),
  (2, 'تفعيل المصادقة المتعددة', 0, 3),
  (3, 'تحديث الأسعار', 0, 1),
  (3, 'عرض تقارير الإيرادات', 1, 2),
  (3, 'إدارة بطاقات الدفع المخزنة', 0, 3);
