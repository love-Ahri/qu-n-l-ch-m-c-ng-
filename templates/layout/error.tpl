{extends file="layout/main.tpl"}

{block name="content"}
<div class="text-center py-5">
    <h1 class="display-1 fw-bold text-gradient">{$error_code}</h1>
    <p class="fs-5 text-secondary mb-4">{$error_message|default:'Đã xảy ra lỗi.'}</p>
    <a href="{$base_url}/" class="btn btn-primary">
        <i class="bi bi-house"></i> Quay lại Trang chủ
    </a>
</div>
{/block}
