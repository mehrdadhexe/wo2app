
<div class="woo2app-Modal" style="display: none;">
    <div class="woo2app-Modal-header">
        <div>
            <button class="woo2app-Modal-return woo2app-icon-chevron-left"> بازگشت </button>
            <h2> ارسال بازخورد </h2>
        </div>
        <button class="woo2app-Modal-close woo2app-icon-close"> بستن </button>
    </div>
    <div class="woo2app-Modal-content">
        <div class="woo2app-Modal-question woo2app-isOpen">
            <h3> ممکن است کمی اطلاعات در مورد غیر فعال کردن پلاگین به ما بدهید ؟ </h3>
            <ul>
                <li>
                    <input type="radio" name="reason"  id="reason-temporary" value="موقت غیر فعال میکنم">
                    <label for="reason-temporary"> موقت غیر فعال میکنم </label>
                    <div class="woo2app-Modal-fieldHidden">
                        <textarea name="reason-other-details"  placeholder="اگر توضیحاتی دارید ، اینجا وارد کنید"></textarea>
                    </div>
                </li>
                <li>
                    <input type="radio" name="reason" id="reason-broke" value="اپلیکیشن اصلا اجرا نشد و کار نکرد.">
                    <label for="reason-broke"> اپلیکیشن اصلا اجرا نشد و کار نکرد. </label>
                    <div class="woo2app-Modal-fieldHidden">
                        <textarea name="reason-other-details"  placeholder="اگر توضیحاتی دارید ، اینجا وارد کنید"></textarea>
                    </div>
                </li>
                <li>
                    <input type="radio" name="reason" id="reason-score" value="فقط تست می کردم ، قصد فعالسازی همیشگی نداشتم">
                    <label for="reason-score"> فقط تست می کردم ، قصد فعالسازی همیشگی نداشتم </label>
                    <div class="woo2app-Modal-fieldHidden">
                        <textarea name="reason-other-details"  placeholder="اگر توضیحاتی دارید ، اینجا وارد کنید"></textarea>
                    </div>
                </li>
                <li>
                    <input type="radio" name="reason" id="reason-loading" value="پلاگین با سایت من تداخل دارد و باعث خرابی آن شده است">
                    <label for="reason-loading"> پلاگین با سایت من تداخل دارد و باعث خرابی آن شده است </label>
                    <div class="woo2app-Modal-fieldHidden">
                        <textarea name="reason-other-details"  placeholder="اگر توضیحاتی دارید ، اینجا وارد کنید"></textarea>
                    </div>
                </li>
                <li>
                    <input type="radio" name="reason" id="reason-complicated" value=" امکاناتی که انتظار داشتم در اپلیکیشن وجود نداشت و برای من کارایی نداشت">
                    <label for="reason-complicated"> امکاناتی که انتظار داشتم در اپلیکیشن وجود نداشت و برای من کارایی نداشت </label>
                    <div class="woo2app-Modal-fieldHidden">
                        <textarea name="reason-other-details"  placeholder="اگر توضیحاتی دارید ، اینجا وارد کنید"></textarea>
                    </div>
                </li>
                <li>
                    <input type="radio" name="reason" id="reason-other" value="دلایل دیگر">
                    <label for="reason-other"> دلایل دیگر </label>
                    <div class="woo2app-Modal-fieldHidden">
                        <textarea name="reason-other-details"  placeholder="اگر توضیحاتی دارید ، اینجا وارد کنید"></textarea>
                    </div>
                </li>
            </ul>
            <input id="woo2app-reason" type="hidden" value="">
            <input id="woo2app-details" type="hidden" value="">
        </div>
    </div>
    <div class="woo2app-Modal-footer">
        <div>
            <button onclick="send_feedback('<?= $data['deactivation_url'] ?>')" class="button button-primary"  > ارسال فرم و غیر فعال کردن پلاگین </button>
            <button class="woo2app-Modal-cancel"> بازگشت </button>
        </div>
        <a href="<?php echo esc_attr( $data['deactivation_url'] ); ?>" class="button button-secondary"> فقط غیر فعال کن </a>
    </div>
</div>
<div class="woo2app-Modal-overlay"></div>
