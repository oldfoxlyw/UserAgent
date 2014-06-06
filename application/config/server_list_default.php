<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

//电信列表
$config ['game_server_list1'] = array (
		'message' => 'SERVER_LIST_SUCCESS',
		'activate' => 0,
		'server' => array (
				array (
						'id' => '1',
						'game_id' => 'B',
						'section_id' => '301',
						'account_server_id' => '301',
						'server_name' => 'arab',
						'server_ip' => array (
								0 => array (
										'ip' => '85.195.100.234:8091' 
								)
						),
						'server_game_ip' => '85.195.100.236',
						'game_message_ip' => '10.11.12.15:9899',
						'server_max_player' => '100000',
						'account_count' => '0',
						'server_language' => '',
						'server_sort' => '1',
						'server_recommend' => '1',
						'server_debug' => '0',
						'partner' => 'arab_default,arab_sdk',
						'version' => '',
						'server_status' => '1',
						'server_new' => '1',
						'special_ip' => '',
						'need_activate' => '0',
						'server_starttime' => '0',
						'server_game_port' => '9999' 
				)
		)
);

$config['game_announcement'] = array(
		'announce' => array (
				'id' => '1',
				'summary' => 'اللاعبين الأعزاء:
"عرش النيران والجليد"، أول لعبة قتالية اون لاين ذات ثلاثية الابعاد على الجوال، لعبة خاصة بالعرب، إطلاق في أبل ستور.نرحبكم للانضمام إلى سيرفر جديد "نور الفجر".

حدث ثابت: 
اسم الحدث: خصم لأول و ثاني شحن
طريقة الحصول: الحصول مباشر بعد الشحن
تفاصيل: أول شحن 19.99$ أو أكثر تتمتع %50 خصم،  كلما تشتري أكثر كلما كان الحصول أكثر، فرصة واحدة فقط! أول شحن 19.99$ أو أكثر تصبح Vip2 فورا (يمكن تسجيل الدخول لاستلام 50 ألماس أخضر كل يوم لخمسة أيام متواصلة!). تربح أيضا ذهب 20000.',
				'content' => 'اللاعبين الأعزاء:
"عرش النيران والجليد"، أول لعبة قتالية اون لاين ذات ثلاثية الابعاد على الجوال، لعبة خاصة بالعرب، إطلاق في أبل ستور.نرحبكم للانضمام إلى سيرفر جديد "نور الفجر".

حدث ثابت: 
اسم الحدث: خصم لأول و ثاني شحن
طريقة الحصول: الحصول مباشر بعد الشحن
تفاصيل: أول شحن 19.99$ أو أكثر تتمتع %50 خصم،  كلما تشتري أكثر كلما كان الحصول أكثر، فرصة واحدة فقط! أول شحن 19.99$ أو أكثر تصبح Vip2 فورا (يمكن تسجيل الدخول لاستلام 50 ألماس أخضر كل يوم لخمسة أيام متواصلة!). تربح أيضا ذهب 20000.

اسم الحدث: اربح هدايا للشحن المتراكم
طريقة الحصول: شحن متراكم وصل العدد المعين
تفاصيل: شحن متراكم وصل 500، 1000، 3000، 6000، 10000، 30000 حصول على الحزمة المختلفة، فيه التبع القوي، ألماس أخضر، عتاد وإلخ.

اسم الحدث: نعمة بطل القمر
وقت الحدث: يوم السبت 22:00-21:00 
تفاصيل: سيعطي فتاة سماوية نعمة الى أرض في ضوء القمر في نهاية الأسبوع. سترقي خبرة وذهب اذا دخول المرحلة في هذه الفترة.

اسم الحدث: يوم المصارعة لآريس
وقت الحدث: يوم الاحد 22:00-21:00
تفاصيل: سضاعف الشرف في المواقع الحربية من نظر المحارب! قتل باستخدام سلاحك!

اسم الحدث: مسابقة الترقية لمستوى10
شروط الحدث: اللاعبين تحت مستوى 10
طريقة الحصول: حصول الحزمة يدوي بعد نجاح ترقي المستوى
تفاصيل: إذا وصلت إلى مستوى10 في غضون ساعة يمكن الحصول على جوائز سخية مثل الذهب والجرعات الفاخرة.

اسم الحدث: مسابقة الترقية لمستوى23
شروط الحدث: اللاعبين فوق مستوى 10 وتحت مستوى23
طريقة الحصول: حصول الحزمة يدوي بعد نجاح ترقي المستوى
تفاصيل: إذا وصلت إلى مستوى23 في غضون 48 ساعة يمكن الحصول على جوائز سخية مثل الذهب السخي والجوهرات الفاخرة.

اسم الحدث: اصطياد وحش الذهب
وقت الحدث: 13:00- 12:00 كل يوم
نمط التحدي: مهمة مرحلة للاعب واحد
تفاصيل: اصطياد وحش الذهب، يسقط قرن الوحش غالي السعر

اسم الحدث: وحش غرين الجوهرة
وقت الحدث: 22:00-20:00 كل يوم
نمط التحدي: مهمة مرحلة للاعب واحد
تفاصيل: قتل  الجوهرة الحمراء القوي للحصول على جوهرة ثمينة.
اسم الحدث: كنز تايتنز القديم
وقت الحدث: يوم الاثنين، يوم الاربعاء، يوم الجمعة
نمط التحدي: مهمة مرحلة للاعب واحد
تفاصيل: ادخل كنز تايتنز القديم، تحدي تايتنز العريق، حصول على صندوق تايتنز!

اسم الحدث: ملتوي سراب الباطل
وقت الحدث: يوم الثلاثاء، يوم الخميس، يوم السبت
نمط التحدي: مهمة مرحلة للاعب واحد
تفاصيل: دخل شيطان الباطل عالمنا الآن، حصول على ذهب، جوهرة  وإلخ.

اسم الحدث: فضاء الموت
وقت الحدث: كل يوم
نمط التحدي: فريق
تفاصيل: اذهب الى ثعبان"مبتلع"! الناجح لفرق أقوى فقط! حصول على ذهب، جوهرة فاخرة!

اسم الحدث: تجارة بلاد الذهب
وقت الحدث: يوم الاربعاء
تفاصيل: تاجر من بلاد غريب سوف يبدل عددا كبيرا من الذهب بالألماس الأخضر بنسبة كبيرة.

اسم الحدث: خصم على جوهرة م3
تفاصيل: خصم %30 لشراء مجموعة جوهرة م3، عدد الجوهرة 60 حبات، تربح كثير حجر مثقب.

أي مشكلة أو شك،  الاتصال بنا:
 فيس بوك: https://www.facebook.com/areshalniran
بريد الخدمة: areshalniran@outlook.com',
				'post_time' => '1394121601',
				'partner_key' => 'default,default_full,91,17173,pp,Downjoy,zq,uc'
		)
);
?>
