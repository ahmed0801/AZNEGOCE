<!doctype html>
<html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>{{ $subject }}</title></head>
<body style="margin:0;padding:0;background:#ECEFF1;font-family:Arial,Helvetica,sans-serif;color:#11161D">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#ECEFF1;padding:24px 12px"><tr><td align="center">
  <table role="presentation" width="560" cellpadding="0" cellspacing="0" style="max-width:560px;width:100%;background:#ffffff;border-radius:14px;overflow:hidden">
    <tr><td style="background:{{ $brand }};padding:26px 28px;color:#ffffff">
      <div style="font-size:13px;opacity:.85">{{ $company }}</div>
      <div style="font-size:26px;font-weight:800;line-height:1.15;margin-top:6px">{{ $promo }}</div>
      <div style="font-size:14px;margin-top:8px;opacity:.92">Du {{ $from }} au {{ $to }}</div>
    </td></tr>
    <tr><td style="padding:26px 28px;font-size:15px;line-height:1.6">
      <p style="margin:0 0 14px">Bonjour {{ $name }},</p>
      <div style="margin:0 0 20px">{!! nl2br(e($message)) !!}</div>
      <p style="margin:0 0 22px"><a href="{{ $url }}" style="display:inline-block;background:{{ $brand }};color:#ffffff;text-decoration:none;font-weight:700;padding:13px 24px;border-radius:10px">Voir les promotions</a></p>
      <p style="margin:0;font-size:13px;color:#7B8794">Les prix promotionnels s'affichent dans votre espace client, calculés pour votre compte.</p>
    </td></tr>
    <tr><td style="padding:16px 28px;border-top:1px solid #E8ECEF;font-size:12px;color:#7B8794">{{ $company }}</td></tr>
  </table>
</td></tr></table>
</body></html>
