<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@62..125,400..800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
<style>
:root{
 --brand:{{ preg_match('/^#[0-9A-Fa-f]{6}$/', (string) $s->primary_color) ? $s->primary_color : '#1E2D4A' }};
 --ground:#ECEFF1;--surface:#fff;--ink:#11161D;--ink2:#46505C;--ink3:#7B8794;--line:#D9DFE4;--line2:#E8ECEF;
 --brand-ink:color-mix(in srgb,var(--brand) 78%,#000);
 --wash:color-mix(in srgb,var(--brand) 7%,var(--surface));
 --wash2:color-mix(in srgb,var(--brand) 14%,var(--surface));
 --edge:color-mix(in srgb,var(--brand) 28%,var(--surface));
 --ok:#0F7B55;--okbg:#DCF2E8;--low:#9A5800;--lowbg:#FBEACB;--out:#B3261E;--outbg:#F8DCD9;
 --font:"Archivo",system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif;
 --mono:"JetBrains Mono",ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;
 --shadow:0 18px 50px rgba(17,22,29,.18);
}
@media (prefers-color-scheme:dark){:root{--ground:#0D1116;--surface:#151B22;--ink:#E7ECF1;--ink2:#A8B3BF;--ink3:#7D8895;--line:#27313B;--line2:#1F2832;--brand-ink:color-mix(in srgb,var(--brand) 55%,#fff);--ok:#4CC79A;--okbg:#10382C;--low:#F2B84B;--lowbg:#3A2A0B;--out:#F2877F;--outbg:#3F1714;--shadow:0 18px 50px rgba(0,0,0,.5)}}
*{box-sizing:border-box}[hidden]{display:none!important}html{-webkit-text-size-adjust:100%}
body{margin:0;background:var(--ground);color:var(--ink);font:400 15px/1.45 var(--font);-webkit-font-smoothing:antialiased;font-variant-numeric:tabular-nums}
a{color:var(--brand-ink)}button,input,select,textarea{font:inherit;color:inherit}button{cursor:pointer}
:focus-visible{outline:2px solid var(--brand);outline-offset:2px;border-radius:6px}
.ic{width:20px;height:20px;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;flex:none}
.ic.sm{width:16px;height:16px}.ic.fill{fill:currentColor}
mark{background:color-mix(in srgb,var(--brand) 22%,transparent);color:inherit;border-radius:3px;padding:0 1px}
.sr{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0)}
/* ─────────── en-tête ─────────── */
.hd{position:sticky;top:0;z-index:50;background:var(--surface);border-top:3px solid var(--brand);border-bottom:1px solid var(--line);box-shadow:0 6px 24px rgba(17,22,29,.06)}
.hd-in{max-width:1320px;margin:0 auto;padding:10px 20px;display:grid;grid-template-columns:auto minmax(0,1fr) auto;gap:18px;align-items:center}
.logo{display:flex;align-items:center;gap:10px;text-decoration:none;color:var(--ink);min-width:0}
.logo img{height:36px;max-width:120px;object-fit:contain}
.mark{width:36px;height:36px;border-radius:9px;background:var(--brand);color:#fff;display:grid;place-items:center;font-weight:800;font-stretch:80%;font-size:1.05rem;flex:none}
.logo b{font-stretch:78%;font-weight:800;font-size:1.2rem;letter-spacing:.01em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.omni{position:relative;max-width:760px;width:100%;justify-self:center}
.omni-box{display:flex;align-items:center;gap:6px;height:48px;border:1.5px solid var(--line);background:var(--ground);border-radius:14px;padding:0 6px 0 14px;transition:border-color .15s,background .15s,box-shadow .15s}
.omni-box:focus-within{border-color:var(--brand);background:var(--surface);box-shadow:0 0 0 4px var(--wash2)}
.omni-box>.ic{color:var(--ink3)}
.omni-box input{flex:1;min-width:0;height:100%;border:0;background:none;outline:0;font-size:1.02rem;font-weight:500}
.omni-box input::placeholder{color:var(--ink3);font-weight:400}
.omni-box input::-webkit-search-cancel-button{display:none}
.omni-kind{font-size:.72rem;font-weight:700;color:var(--brand-ink);background:var(--wash2);border-radius:99px;padding:3px 9px;white-space:nowrap}
.ibtn{width:38px;height:38px;border:0;background:none;border-radius:10px;display:grid;place-items:center;color:var(--ink2);position:relative;flex:none}
.ibtn:hover{background:var(--wash)}
.omni-box kbd{font:600 .72rem var(--mono);border:1px solid var(--line);border-radius:6px;padding:1px 7px;color:var(--ink3);background:var(--surface)}
.omni-box input:focus~kbd,.omni-box input:not(:placeholder-shown)~kbd{display:none}
.panel{position:absolute;left:0;right:0;top:calc(100% + 8px);background:var(--surface);border:1px solid var(--line);border-radius:16px;box-shadow:var(--shadow);overflow:hidden;max-height:min(72vh,640px);overflow-y:auto}
.sg-h{display:flex;justify-content:space-between;align-items:center;padding:12px 16px 6px;font-size:.78rem;font-weight:700;color:var(--ink3)}
.sg-h button{border:0;background:none;color:var(--brand-ink);font-weight:600;font-size:.78rem;padding:2px 4px}
.sg-i{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:2px 14px;align-items:center;padding:9px 16px;cursor:pointer;border:0;background:none;width:100%;text-align:left}
.sg-i.act,.sg-i:hover{background:var(--wash)}
.sg-ref{font:700 .9rem var(--mono);letter-spacing:.01em}
.sg-name{grid-column:1;color:var(--ink2);font-size:.88rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.sg-side{grid-column:2;grid-row:1/3;text-align:right;display:flex;flex-direction:column;align-items:flex-end;gap:3px}
.sg-p{font-weight:700;font-stretch:85%;font-size:1rem}
.sg-chips{display:flex;flex-wrap:wrap;gap:6px;padding:4px 16px 12px}
.chipb{border:1px solid var(--line);background:var(--surface);border-radius:99px;padding:5px 12px;font-size:.85rem;font-weight:600;display:inline-flex;gap:7px;align-items:center}
.chipb small{color:var(--ink3);font-weight:500}.chipb:hover,.chipb.act{border-color:var(--brand);background:var(--wash)}
.sg-foot{display:flex;justify-content:space-between;align-items:center;gap:10px;padding:11px 16px;border-top:1px solid var(--line2);background:var(--ground);font-size:.8rem;color:var(--ink3)}
.sg-foot .go{border:0;background:none;color:var(--brand-ink);font-weight:700;font-size:.9rem;padding:2px 0;text-align:left}
.sg-foot kbd{font:600 .7rem var(--mono);border:1px solid var(--line);border-radius:5px;padding:0 5px;background:var(--surface)}
.sg-empty{padding:22px 16px;color:var(--ink2);font-size:.92rem}
.sg-empty b{display:block;color:var(--ink);margin-bottom:2px}
.tools{display:flex;align-items:center;gap:6px}
.tbtn{height:44px;min-width:44px;padding:0 12px;border:1px solid var(--line);background:var(--surface);border-radius:12px;display:inline-flex;align-items:center;gap:8px;font-weight:600;font-size:.9rem;position:relative}
.tbtn:hover{border-color:var(--edge);background:var(--wash)}
.tbtn .n{position:absolute;top:-6px;right:-6px;min-width:20px;height:20px;border-radius:99px;background:var(--brand);color:#fff;font-size:.72rem;font-weight:800;display:grid;place-items:center;padding:0 5px}
.tbtn.cartb{background:var(--brand);border-color:var(--brand);color:#fff}.tbtn.cartb:hover{background:var(--brand-ink)}
.tbtn.cartb .n{background:#fff;color:var(--brand)}
.tbtn .sum{font-stretch:85%;font-weight:700}
.bump{animation:bump .35s ease}@keyframes bump{40%{transform:scale(1.14)}}
.me{position:relative}
.av{width:30px;height:30px;border-radius:50%;background:var(--wash2);color:var(--brand-ink);display:grid;place-items:center;font-weight:800;font-size:.85rem}
.menu{position:absolute;right:0;top:calc(100% + 8px);background:var(--surface);border:1px solid var(--line);border-radius:14px;box-shadow:var(--shadow);min-width:240px;padding:6px;display:none;z-index:60}
.menu.open{display:block}
.menu .who{padding:10px 12px 12px;border-bottom:1px solid var(--line2);margin-bottom:4px}.menu .who b{display:block}.menu .who span{color:var(--ink3);font-size:.84rem}
.menu a,.menu button{display:block;width:100%;text-align:left;padding:9px 12px;border:0;background:none;border-radius:8px;text-decoration:none;color:var(--ink);font-size:.92rem}
.menu a:hover,.menu button:hover{background:var(--wash)}
/* ─────────── page ─────────── */
.page{max-width:1320px;margin:0 auto;padding:20px 20px 90px}
.notes{display:grid;gap:8px;margin-bottom:14px}
.note{border-radius:12px;padding:10px 14px;font-size:.9rem;border:1px solid}
.note.warn{background:var(--lowbg);border-color:color-mix(in srgb,var(--low) 30%,transparent);color:var(--low)}
.note.bad{background:var(--outbg);border-color:color-mix(in srgb,var(--out) 30%,transparent);color:var(--out)}
.note.info{background:var(--wash);border-color:var(--edge);color:var(--brand-ink)}
.shell{display:grid;grid-template-columns:268px minmax(0,1fr);gap:22px;align-items:start}
.shell.is-browse{grid-template-columns:minmax(0,1fr)}.shell.is-browse .side{display:none}
.side{position:sticky;top:92px;max-height:calc(100vh - 110px);overflow:auto;background:var(--surface);border:1px solid var(--line);border-radius:16px;padding:6px 16px 14px}
.fs{padding:12px 0;border-bottom:1px solid var(--line2)}.fs:last-child{border-bottom:0}
.fs h3{margin:0 0 8px;font-size:.92rem;font-weight:700;display:flex;justify-content:space-between;align-items:center}
.fs h3 button{border:0;background:none;color:var(--brand-ink);font-weight:600;font-size:.8rem}
.fopt{display:flex;align-items:center;gap:10px;padding:5px 2px;cursor:pointer;font-size:.9rem;border-radius:6px}
.fopt:hover{background:var(--wash)}
.fopt input{appearance:none;width:18px;height:18px;border:1.5px solid var(--ink3);border-radius:5px;margin:0;display:grid;place-items:center;flex:none;background:var(--surface)}
.fopt input:checked{background:var(--brand);border-color:var(--brand)}
.fopt input:checked::after{content:"";width:9px;height:5px;border:2px solid #fff;border-top:0;border-right:0;transform:rotate(-45deg) translate(1px,-1px)}
.fopt span{flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.fopt small{color:var(--ink3);font-size:.78rem}
.fsearch{width:100%;height:34px;border:1px solid var(--line);border-radius:9px;padding:0 10px;margin-bottom:6px;background:var(--ground);font-size:.88rem}
.more{border:0;background:none;color:var(--brand-ink);font-weight:600;font-size:.84rem;padding:6px 2px}
.clearf{width:100%;margin-top:6px;border:1px solid var(--line);background:var(--surface);border-radius:10px;height:38px;font-weight:600}
.bar{display:flex;flex-wrap:wrap;gap:10px 14px;align-items:center;margin-bottom:12px}
.bar-l{flex:1;min-width:220px;font-size:.95rem;color:var(--ink2)}.bar-l b{color:var(--ink);font-size:1.15rem;font-stretch:85%}
.approx{display:block;margin-top:2px;font-size:.84rem;color:var(--low)}
.bar-r{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
.sel{height:40px;border:1px solid var(--line);background:var(--surface);border-radius:10px;padding:0 10px;font-size:.9rem;font-weight:500;max-width:100%}
.seg{display:inline-flex;border:1px solid var(--line);border-radius:10px;overflow:hidden;background:var(--surface)}
.seg button{width:40px;height:38px;border:0;background:none;display:grid;place-items:center;color:var(--ink3)}
.seg button.on{background:var(--wash2);color:var(--brand-ink)}
.fbtn{display:none}
.chips{display:flex;flex-wrap:wrap;gap:6px;margin:0 0 12px}
.chip{display:inline-flex;align-items:center;gap:6px;background:var(--wash2);color:var(--brand-ink);border-radius:99px;padding:4px 6px 4px 12px;font-size:.84rem;font-weight:600}
.chip button{width:20px;height:20px;border:0;border-radius:50%;background:color-mix(in srgb,var(--brand) 20%,transparent);color:inherit;display:grid;place-items:center;padding:0}
/* ─────────── lignes d'articles ─────────── */
.parts{background:var(--surface);border:1px solid var(--line);border-radius:16px;overflow:hidden}
.part{display:grid;grid-template-columns:210px minmax(0,1fr) 128px 156px 224px;grid-template-areas:"ref main stock price buy" "eq eq eq eq eq";gap:6px 18px;align-items:center;padding:13px 18px;border-top:1px solid var(--line2);transition:background .12s}
.part:first-child{border-top:0}.part:hover{background:color-mix(in srgb,var(--brand) 3%,var(--surface))}
.c-ref{grid-area:ref;display:flex;flex-direction:column;gap:5px;align-items:flex-start;min-width:0}
.plate{max-width:100%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font:700 .92rem var(--mono);letter-spacing:.02em;padding:6px 11px;border-radius:8px;border:1.5px solid var(--edge);background:var(--wash);color:var(--brand-ink)}
.plate:hover{background:var(--wash2);border-color:var(--brand)}
.brand{font-size:.78rem;font-weight:700;color:var(--ink2);padding-left:3px;letter-spacing:.01em}
.c-main{grid-area:main;min-width:0}
.name{display:block;border:0;background:none;padding:0;text-align:left;font-weight:600;font-size:1rem;line-height:1.3;color:var(--ink)}
.name:hover{color:var(--brand-ink);text-decoration:underline;text-underline-offset:3px}
.meta{display:flex;flex-wrap:wrap;gap:2px 14px;margin-top:3px;font-size:.8rem;color:var(--ink3)}
.meta .tec{font-family:var(--mono);font-size:.74rem}
.c-stock{grid-area:stock}
.stk{display:inline-flex;align-items:center;gap:7px;font-weight:600;font-size:.84rem;border-radius:99px;padding:4px 11px;white-space:nowrap}
.stk i{width:8px;height:8px;border-radius:50%;background:currentColor}
.stk.in{background:var(--okbg);color:var(--ok)}.stk.low{background:var(--lowbg);color:var(--low)}.stk.out{background:var(--outbg);color:var(--out)}
.c-price{grid-area:price;text-align:right;line-height:1.2}
.net{font-stretch:78%;font-weight:700;font-size:1.55rem;letter-spacing:-.01em}.net small{font-size:.72rem;font-weight:600;color:var(--ink3);margin-left:4px;font-stretch:100%}
.ttc{font-size:.78rem;color:var(--ink3);margin-top:1px}
.was{display:flex;gap:7px;justify-content:flex-end;align-items:center;margin-top:3px;font-size:.78rem;color:var(--ink3)}.was s{opacity:.85}
.tag{border-radius:6px;padding:1px 7px;font-weight:700;font-size:.74rem}
.tag.off{background:var(--okbg);color:var(--ok)}.tag.promo{background:#fde7e9;color:#b3202e}.tag.pn{background:var(--wash2);color:var(--brand-ink)}
.ask{font-size:.85rem;color:var(--ink3)}
.c-buy{grid-area:buy;display:flex;gap:8px;align-items:center;justify-content:flex-end;flex-wrap:wrap}
.qty{display:inline-flex;height:42px;border:1.5px solid var(--line);border-radius:11px;overflow:hidden;background:var(--surface)}
.qty button{width:34px;border:0;background:none;font-size:1.1rem;color:var(--ink2)}.qty button:hover{background:var(--wash)}
.qty input{width:44px;border:0;text-align:center;font-weight:700;background:none;outline:0;-moz-appearance:textfield}
.qty input::-webkit-outer-spin-button,.qty input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}
.qty:focus-within{border-color:var(--brand)}
.add{height:42px;padding:0 18px;border:0;border-radius:11px;background:var(--brand);color:#fff;font-weight:700;display:inline-flex;align-items:center;gap:7px;transition:background .15s}
.add:hover{background:var(--brand-ink)}.add.done{background:var(--ok)}.add:disabled{opacity:.6;cursor:wait}
.fav{width:42px;height:42px;border:1.5px solid var(--line);background:var(--surface);border-radius:11px;display:grid;place-items:center;color:var(--ink3)}
.fav:hover{border-color:var(--edge);color:var(--brand-ink)}.fav.on{color:#C98A00;border-color:#E6C46B;background:color-mix(in srgb,#E6B83A 14%,var(--surface))}.fav.on .ic{fill:currentColor}
.incart{flex-basis:100%;text-align:right;font-size:.78rem;color:var(--ok);font-weight:600}
.c-eq{grid-area:eq;display:flex;gap:10px;align-items:center;flex-wrap:wrap}
.eqbtn{border:1px solid var(--edge);background:var(--wash);color:var(--brand-ink);border-radius:9px;padding:5px 12px;font-weight:700;font-size:.82rem;display:inline-flex;align-items:center;gap:7px}
.eqbtn:hover{background:var(--wash2)}.eqhint{font-size:.82rem;color:var(--low)}
.eqbox{grid-area:eq;margin-top:4px}.eqbox .parts{border-radius:12px;background:var(--ground);border-style:dashed}
.eqbox .part{grid-template-columns:170px minmax(0,1fr) 120px 140px 200px;padding:10px 12px}
.part.is-out .plate{opacity:.75}
/* grille */
.parts.is-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(262px,1fr));gap:14px;background:none;border:0;border-radius:0;overflow:visible}
.is-grid .part{grid-template-columns:1fr;grid-template-areas:"ref" "main" "stock" "price" "buy" "eq";gap:9px;border:1px solid var(--line);border-radius:16px;background:var(--surface);padding:16px;align-content:start}
.is-grid .part:first-child{border-top:1px solid var(--line)}
.is-grid .c-price{text-align:left}.is-grid .was{justify-content:flex-start}
.is-grid .c-buy{justify-content:flex-start}.is-grid .incart{text-align:left}
.pager{display:flex;gap:10px;justify-content:center;align-items:center;margin:22px 0 4px}
.pager button{height:42px;padding:0 18px;border-radius:11px;border:1px solid var(--line);background:var(--surface);font-weight:600}
.pager button:disabled{opacity:.4;cursor:default}.pager span{color:var(--ink3);font-size:.9rem}
.sect{display:flex;align-items:baseline;gap:10px;margin:26px 2px 10px;font-weight:700;font-size:1.05rem;font-stretch:90%}.sect small{font-weight:400;color:var(--ink3);font-size:.86rem}
.empty{background:var(--surface);border:1px dashed var(--line);border-radius:16px;padding:44px 22px;text-align:center;color:var(--ink2)}
.empty b{display:block;font-size:1.15rem;color:var(--ink);margin-bottom:6px}
.empty .acts{display:flex;gap:8px;justify-content:center;flex-wrap:wrap;margin-top:14px}
.btn2{height:42px;padding:0 16px;border-radius:11px;border:1px solid var(--line);background:var(--surface);font-weight:600;display:inline-flex;align-items:center;gap:8px;text-decoration:none;color:var(--ink)}
.btn2:hover{border-color:var(--edge);background:var(--wash)}.btn2.pri{background:var(--brand);border-color:var(--brand);color:#fff}.btn2.pri:hover{background:var(--brand-ink)}
.loading .parts,.loading .bar{opacity:.4;transition:opacity .12s}
.sk{height:76px;border-top:1px solid var(--line2);background:linear-gradient(90deg,transparent,color-mix(in srgb,var(--brand) 6%,transparent),transparent);background-size:200% 100%;animation:sh 1.1s linear infinite}
@keyframes sh{to{background-position:-200% 0}}
/* ─────────── accueil ─────────── */
.home h1{font-stretch:75%;font-weight:800;font-size:2.1rem;line-height:1.05;margin:4px 0 4px;letter-spacing:-.01em}
.home .sub{color:var(--ink2);margin:0 0 20px}
.quick{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:12px;margin-bottom:6px}
.qa{display:flex;gap:14px;align-items:center;text-align:left;border:1px solid var(--line);background:var(--surface);border-radius:16px;padding:16px 18px}
.qa:hover{border-color:var(--edge);background:var(--wash)}
.qa .ico{width:44px;height:44px;border-radius:12px;background:var(--wash2);color:var(--brand-ink);display:grid;place-items:center;flex:none}
.qa b{display:block;font-size:1rem}.qa span{font-size:.84rem;color:var(--ink3)}
.index{columns:3 260px;column-gap:28px;background:var(--surface);border:1px solid var(--line);border-radius:16px;padding:10px 20px}
.index a{display:flex;justify-content:space-between;gap:12px;padding:8px 0;border-bottom:1px solid var(--line2);text-decoration:none;color:var(--ink);break-inside:avoid;font-weight:500}
.index a:hover{color:var(--brand-ink)}.index a small{color:var(--ink3);font-size:.82rem}
.brands{display:flex;flex-wrap:wrap;gap:8px}
/* ─────────── tiroirs, fenêtres, toasts ─────────── */
.veil{position:fixed;inset:0;background:rgba(10,14,19,.5);z-index:80;opacity:0;pointer-events:none;transition:opacity .2s}
.veil.on{opacity:1;pointer-events:auto}
.dr{position:fixed;top:0;right:0;bottom:0;width:min(540px,100vw);background:var(--surface);z-index:90;box-shadow:var(--shadow);transform:translateX(102%);transition:transform .26s cubic-bezier(.2,.8,.2,1);display:flex;flex-direction:column}
.dr.on{transform:none}
.dr-h{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:14px 18px;border-bottom:1px solid var(--line)}
.dr-h h2{margin:0;font-size:1.15rem;font-stretch:90%}
.dr-b{flex:1;overflow:auto;padding:18px}
.dr-f{border-top:1px solid var(--line);padding:14px 18px;background:var(--ground)}
.di-top{display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:10px}
.di-name{font-size:1.45rem;line-height:1.15;font-weight:700;font-stretch:88%;margin:0 0 14px}
.di-buy{background:var(--ground);border-radius:14px;padding:14px;display:grid;gap:12px;margin-bottom:18px}
.di-buy .net{font-size:2rem}
.di-buy .c-buy{justify-content:flex-start}
.dl{display:grid;grid-template-columns:130px 1fr;gap:8px 14px;margin:0 0 18px;font-size:.92rem}
.dl dt{color:var(--ink3)}.dl dd{margin:0;font-weight:500}.dl .mono{font-family:var(--mono);font-size:.86rem}
.dr h3{font-size:.95rem;margin:18px 0 8px}
.dr .parts .part{grid-template-columns:minmax(0,1fr);grid-template-areas:"ref" "main" "stock" "price" "buy" "eq";gap:8px}
.dr .parts .c-price{text-align:left}.dr .parts .was{justify-content:flex-start}.dr .parts .c-buy{justify-content:flex-start}
.cl{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:6px 14px;padding:14px 0;border-bottom:1px solid var(--line2)}
.cl .plate{font-size:.8rem;padding:4px 9px}
.cl .nm{font-size:.9rem;margin:5px 0 8px;color:var(--ink2)}
.cl .lt{text-align:right;font-weight:700;font-stretch:85%;font-size:1.1rem}.cl .lt small{display:block;font-weight:400;font-stretch:100%;color:var(--ink3);font-size:.78rem}
.cl .qty{height:36px}.cl .qty button{width:30px}.cl .qty input{width:38px}
.cl .rm{border:0;background:none;color:var(--ink3);padding:6px;border-radius:8px}.cl .rm:hover{color:var(--out);background:var(--outbg)}
.cl .warn{grid-column:1/-1;font-size:.8rem;color:var(--low)}
.tot{display:grid;gap:5px;font-size:.92rem;margin-bottom:12px}.tot div{display:flex;justify-content:space-between}
.tot .ttl{font-size:1.3rem;font-weight:700;font-stretch:85%;margin-top:4px;padding-top:8px;border-top:1px solid var(--line)}
.prog{height:8px;border-radius:99px;background:var(--line);overflow:hidden;margin:6px 0 4px}.prog i{display:block;height:100%;background:var(--brand);border-radius:99px;transition:width .3s}
.mini{font-size:.82rem;color:var(--ink2)}
.cta{width:100%;height:50px;border:0;border-radius:13px;background:var(--brand);color:#fff;font-weight:800;font-size:1rem}
.cta:disabled{background:var(--line);color:var(--ink3);cursor:not-allowed}
.link{border:0;background:none;color:var(--ink3);text-decoration:underline;font-size:.84rem;padding:6px 0}
.modal{position:fixed;left:50%;top:50%;transform:translate(-50%,-48%);width:min(720px,calc(100vw - 24px));max-height:calc(100vh - 40px);background:var(--surface);border-radius:20px;z-index:90;box-shadow:var(--shadow);display:none;flex-direction:column;opacity:0}
.modal.on{display:flex;opacity:1;transform:translate(-50%,-50%);transition:opacity .18s,transform .18s}
.modal textarea{width:100%;min-height:150px;border:1.5px solid var(--line);border-radius:12px;padding:12px;font:500 .92rem/1.5 var(--mono);background:var(--ground);resize:vertical}
.modal textarea:focus{outline:0;border-color:var(--brand);background:var(--surface)}
.bl{width:100%;border-collapse:collapse;font-size:.88rem;margin-top:12px}
.bl td,.bl th{padding:8px 6px;border-bottom:1px solid var(--line2);text-align:left;vertical-align:middle}.bl th{font-size:.78rem;color:var(--ink3);font-weight:600}
.bl .bad{color:var(--out);font-weight:600}.bl .okc{color:var(--ok)}
.scan video{width:100%;border-radius:14px;background:#000;aspect-ratio:4/3;object-fit:cover}
.toasts{position:fixed;left:50%;bottom:22px;transform:translateX(-50%);z-index:120;display:grid;gap:8px;width:min(440px,calc(100vw - 24px))}
.toast{display:flex;gap:12px;align-items:center;justify-content:space-between;background:var(--ink);color:var(--surface);border-radius:13px;padding:12px 14px;box-shadow:var(--shadow);font-size:.92rem;animation:tin .22s ease}
.toast button{border:0;background:none;color:inherit;font-weight:700;text-decoration:underline;padding:0}.toast.err{background:var(--out);color:#fff}
@keyframes tin{from{opacity:0;transform:translateY(8px)}}
.fab{display:none}
/* ─────────── pages d'accès ─────────── */
.auth2{min-height:100vh;display:grid;grid-template-columns:minmax(0,1.05fr) minmax(0,1fr)}
.ab{background:var(--brand);color:#fff;padding:48px 52px;display:flex;flex-direction:column;justify-content:space-between;position:relative;overflow:hidden}
.ab::after{content:"";position:absolute;right:-60px;bottom:-60px;width:380px;height:380px;background:repeating-linear-gradient(135deg,rgba(255,255,255,.07) 0 14px,transparent 14px 28px);border-radius:50%}
.ab>*{position:relative;z-index:1}
.ab .logo{color:#fff}.ab .logo .mark{background:#fff;color:var(--brand)}.ab .logo img{background:#fff;border-radius:8px;padding:3px}
.ab h1{font-stretch:68%;font-weight:800;font-size:clamp(2.6rem,5.4vw,4.6rem);line-height:.95;margin:0 0 16px;letter-spacing:-.015em}
.ab p{margin:0;max-width:34ch;font-size:1.05rem;opacity:.9}
.ab ul{list-style:none;padding:0;margin:30px 0 0;display:grid;gap:12px;font-weight:500}
.ab li{display:flex;gap:12px;align-items:flex-start}
.ab li i{width:22px;height:22px;border-radius:50%;background:rgba(255,255,255,.18);display:grid;place-items:center;flex:none;margin-top:1px}
.ab li i::after{content:"";width:9px;height:5px;border:2px solid #fff;border-top:0;border-right:0;transform:rotate(-45deg) translate(1px,-1px)}
.ab .ct{font-size:.88rem;opacity:.85;line-height:1.7}.ab .ct a{color:#fff;font-weight:700;text-decoration:none}
.af{display:grid;place-items:center;padding:36px 24px;background:var(--surface)}
.af-in{width:100%;max-width:380px}
.af h2{font-stretch:80%;font-size:1.9rem;margin:0 0 6px;font-weight:800}.af .lead{color:var(--ink2);margin:0 0 22px}
.fld{margin-bottom:16px}.fld label{display:block;font-size:.84rem;font-weight:600;margin-bottom:6px}
.fld input{width:100%;height:50px;border:1.5px solid var(--line);border-radius:12px;padding:0 14px;font-size:1rem;background:var(--ground);outline:0;transition:border-color .15s,box-shadow .15s,background .15s}
.fld input:focus{border-color:var(--brand);background:var(--surface);box-shadow:0 0 0 4px var(--wash2)}
.pw{position:relative}.pw button{position:absolute;right:6px;top:7px;height:36px;border:0;background:none;color:var(--ink3);font-weight:700;font-size:.82rem;padding:0 10px;border-radius:8px}
.btn{width:100%;height:52px;border:0;border-radius:13px;background:var(--brand);color:#fff;font-weight:800;font-size:1.02rem}.btn:hover{background:var(--brand-ink)}
.err{background:var(--outbg);color:var(--out);border-radius:11px;padding:11px 13px;font-size:.9rem;margin-bottom:16px}
.okb{background:var(--okbg);color:var(--ok);border-radius:11px;padding:11px 13px;font-size:.9rem;margin-bottom:16px}
.links{margin-top:18px;font-size:.9rem}.rules{font-size:.82rem;color:var(--ink3);margin:-8px 0 16px}
.foot{text-align:center;color:var(--ink3);font-size:.78rem;padding:10px 16px 40px}
/* ─────────── mobile ─────────── */
@media (max-width:1060px){
 .part{grid-template-columns:170px minmax(0,1fr) 150px;grid-template-areas:"ref main price" "stock stock buy" "eq eq eq";row-gap:8px}
 .c-stock{align-self:center}.c-buy{justify-content:flex-end}
 .shell{grid-template-columns:minmax(0,1fr)}
 .side{position:fixed;left:0;right:0;bottom:0;top:auto;max-height:82vh;z-index:90;border-radius:20px 20px 0 0;transform:translateY(105%);transition:transform .26s cubic-bezier(.2,.8,.2,1);box-shadow:var(--shadow)}
 .side.on{transform:none}.shell.is-browse .side{display:block}.shell.is-browse .fbtn{display:none}
 .fbtn{display:inline-flex}
}
@media (max-width:760px){
 .hd-in{grid-template-columns:auto 1fr;padding:8px 12px;gap:8px 10px}
 .logo b{display:none}.omni-box kbd{display:none}.bar-l{min-width:0;flex:none}.bar-r{flex:1;flex-wrap:nowrap;justify-content:flex-end}.sel{flex:1;min-width:0;width:100%}.omni{grid-column:1/-1;grid-row:2;max-width:none}.tools{justify-self:end}
 .tbtn .sum,.tbtn .lbl{display:none}.page{padding:14px 12px 100px}
 .part{grid-template-columns:minmax(0,1fr) auto;grid-template-areas:"ref price" "main main" "stock stock" "buy buy" "eq eq";gap:8px 10px;padding:14px}
 .c-ref{flex-direction:row;align-items:center;gap:8px;flex-wrap:wrap}
 .c-buy{justify-content:flex-start}.c-buy .add{flex:1;justify-content:center}.incart{text-align:left}
 .eqbox .part{grid-template-columns:minmax(0,1fr) auto;grid-template-areas:"ref price" "main main" "stock stock" "buy buy"}
 .home h1{font-size:1.7rem}
 .fab{display:flex;position:fixed;right:14px;bottom:14px;z-index:70;height:54px;padding:0 20px;border-radius:99px;border:0;background:var(--brand);color:#fff;font-weight:800;align-items:center;gap:10px;box-shadow:var(--shadow)}
 .auth2{grid-template-columns:1fr}.ab{padding:28px 24px 30px}.ab ul,.ab .ct{display:none}.ab h1{font-size:2.6rem}.af{padding:26px 20px 40px;align-items:start}
 .modal{width:100vw;max-height:92vh;top:auto;bottom:0;left:0;transform:translateY(20px);border-radius:20px 20px 0 0}.modal.on{transform:none}
}
@media (prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}

/* ─────────── accueil : familles + animations ─────────── */
.fams{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:12px}
.fam{position:relative;overflow:hidden;background:var(--surface);border:1px solid var(--line);border-radius:14px;transition:border-color .2s,box-shadow .2s,transform .2s}
.fam::before{content:"";position:absolute;left:0;top:0;bottom:0;width:4px;background:var(--brand);transform:scaleY(0);transform-origin:top;transition:transform .25s cubic-bezier(.2,.7,.2,1)}
.fam:hover{border-color:var(--edge);box-shadow:0 10px 26px rgba(17,22,29,.09);transform:translateY(-2px)}
.fam:hover::before{transform:scaleY(1)}
.fam-h{display:flex;align-items:center;gap:10px;padding:14px 16px 6px;text-decoration:none;color:var(--ink)}
.fam-n{flex:1;min-width:0;font-weight:700;font-stretch:90%;font-size:1.02rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.fam-c{font:600 .78rem var(--mono);color:var(--ink3)}
.fam-a{color:var(--brand-ink);opacity:0;transform:translateX(-6px);transition:opacity .2s,transform .2s}
.fam:hover .fam-a{opacity:1;transform:none}
.fam-s{display:flex;flex-wrap:wrap;gap:4px 12px;padding:0 16px 14px;font-size:.82rem}
.fam-s a{color:var(--ink2);text-decoration:none}.fam-s a:hover{color:var(--brand-ink);text-decoration:underline;text-underline-offset:3px}
.fam-m{color:var(--ink3)}
.rv{opacity:0;transform:translateY(12px);animation:rv .65s cubic-bezier(.2,.7,.2,1) forwards;animation-delay:calc(var(--d,0) * 70ms)}
@keyframes rv{to{opacity:1;transform:none}}
.qa{transition:transform .2s,border-color .2s,background .2s,box-shadow .2s}
.qa:hover{transform:translateY(-3px);box-shadow:0 12px 28px rgba(17,22,29,.1)}
.qa .ico{transition:transform .25s cubic-bezier(.3,1.6,.5,1),background .2s,color .2s}
.qa:hover .ico{transform:scale(1.12) rotate(-6deg);background:var(--brand);color:#fff}
.chipb{transition:transform .15s,border-color .15s,background .15s}.chipb:hover{transform:translateY(-1px)}
.enter .part{animation:pin .38s cubic-bezier(.2,.7,.2,1) both;animation-delay:calc(var(--i,0) * 30ms)}
@keyframes pin{from{opacity:0;transform:translateY(8px)}}
.fly{position:fixed;z-index:130;width:16px;height:16px;border-radius:50%;background:var(--brand);box-shadow:0 0 0 4px var(--wash2);pointer-events:none}
.home h1{background:linear-gradient(100deg,var(--ink) 55%,var(--brand-ink));-webkit-background-clip:text;background-clip:text;color:transparent}
@media (prefers-reduced-motion:reduce){.rv{opacity:1!important;transform:none!important}.enter .part{opacity:1!important}.fam:hover,.qa:hover{transform:none}}

/* ─────────── commandes ─────────── */
.nav2{display:flex;gap:4px;justify-self:start}.nav2 a{padding:8px 14px;border-radius:10px;text-decoration:none;color:var(--ink2);font-weight:600;font-size:.94rem}
.nav2 a:hover{background:var(--wash)}.nav2 a.on{background:var(--wash2);color:var(--brand-ink)}
.ph{font-stretch:78%;font-weight:800;font-size:1.9rem;margin:6px 0 16px;letter-spacing:-.01em}
.ol{display:grid;gap:10px}
.oi{display:grid;grid-template-columns:130px minmax(0,1fr) 130px 190px;gap:14px;align-items:center;background:var(--surface);border:1px solid var(--line);border-radius:14px;padding:14px 18px;text-decoration:none;color:var(--ink);transition:border-color .15s,transform .15s,box-shadow .15s}
.oi:hover{border-color:var(--edge);transform:translateY(-2px);box-shadow:0 10px 24px rgba(17,22,29,.08)}
.oi .on{font:700 .92rem var(--mono)}.oi small{display:block;color:var(--ink3);font-size:.78rem;font-weight:400}
.oi .ot{text-align:right;font-weight:700;font-stretch:85%;font-size:1.15rem}
.os{display:inline-block;justify-self:end;border-radius:99px;padding:4px 12px;font-weight:700;font-size:.82rem;background:var(--wash2);color:var(--brand-ink)}
.os.st-pending{background:var(--lowbg);color:var(--low)}.os.st-validated{background:var(--okbg);color:var(--ok)}.os.st-refused,.os.st-cancelled{background:var(--outbg);color:var(--out)}
.oh{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap}
.ocard{background:var(--surface);border:1px solid var(--line);border-radius:14px;padding:16px 18px;margin-top:14px}
.ot2{width:100%;border-collapse:collapse;font-size:.92rem}.ot2 th,.ot2 td{padding:11px 14px;border-top:1px solid var(--line2);text-align:left;vertical-align:top}
.ot2 thead th{border-top:0;font-size:.78rem;color:var(--ink3);font-weight:600;background:var(--ground)}.ot2 .r{text-align:right;white-space:nowrap}
.cform{display:grid;gap:14px}.cform label{font-size:.84rem;font-weight:600;display:block;margin-bottom:6px}
.cform input[type=text],.cform textarea{width:100%;border:1.5px solid var(--line);border-radius:12px;padding:11px 13px;background:var(--ground);outline:0}
.cform input:focus,.cform textarea:focus{border-color:var(--brand);background:var(--surface)}
.modes{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.mode{border:1.5px solid var(--line);border-radius:12px;padding:12px 14px;cursor:pointer;display:block}.mode input{position:absolute;opacity:0}
.mode b{display:block}.mode span{font-size:.8rem;color:var(--ink3)}.mode:has(input:checked){border-color:var(--brand);background:var(--wash)}
.done-box{text-align:center;padding:30px 10px}.done-box .ok{width:64px;height:64px;border-radius:50%;background:var(--okbg);color:var(--ok);display:grid;place-items:center;margin:0 auto 14px}
.done-box .ok .ic{width:32px;height:32px;stroke-dasharray:30;stroke-dashoffset:30;animation:draw .6s .15s ease forwards}@keyframes draw{to{stroke-dashoffset:0}}
.done-box h3{font-size:1.3rem;margin:0 0 6px;font-stretch:85%}
@media (max-width:760px){.oi{grid-template-columns:1fr auto;grid-template-areas:"n s" "d t"}.oi .on{grid-area:n}.oi .od{grid-area:d}.oi .ot{grid-area:t}.oi .os{grid-area:s}.nav2 a{padding:8px 10px}.hd-in .logo b{display:none}.ot2 th:nth-child(4),.ot2 td:nth-child(4){display:none}}
/* ─────────── suivi de commande + espace compte ─────────── */
.os.mod{background:var(--lowbg);color:var(--low)}
.tl{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:0;margin:18px 0 6px;position:relative}
.tl .st{position:relative;padding-top:30px;text-align:center;font-size:.84rem;color:var(--ink3);line-height:1.3}
.tl .st:before{content:"";position:absolute;top:9px;left:0;right:0;height:3px;background:var(--line)}
.tl .st:first-child:before{left:50%}.tl .st:last-child:before{right:50%}
.tl .st:after{content:"";position:absolute;top:2px;left:calc(50% - 8px);width:16px;height:16px;border-radius:50%;background:var(--surface);border:3px solid var(--line)}
.tl .st b{display:block;color:var(--ink);font-size:.9rem}.tl .st small{display:block;font-size:.76rem}
.tl .st.done{color:var(--ink2)}.tl .st.done:before{background:var(--ok)}.tl .st.done:after{background:var(--ok);border-color:var(--ok)}
.tl .st.done.mod:after{background:var(--low);border-color:var(--low)}
.tl .st.bad:after{background:var(--out);border-color:var(--out)}.tl .st.bad:before{background:var(--out)}
.tl .st.now:after{border-color:var(--brand);box-shadow:0 0 0 5px color-mix(in srgb,var(--brand) 22%,transparent)}
.tl .st a{color:var(--brand-ink);font-weight:700;text-decoration:none}
.chg{margin:14px 0 0;display:grid;gap:6px}.chg .note{font-size:.88rem}
.ot2 tr.gone td{opacity:.55;text-decoration:line-through}.ot2 .diff{display:block;font-size:.78rem;color:var(--low);font-weight:600;text-decoration:none}
.ot2 .diff.rm{color:var(--out)}.ot2 .old{color:var(--ink3);text-decoration:line-through;font-weight:400;margin-right:6px}
.acct-tabs{display:flex;gap:6px;margin:0 0 16px;border-bottom:1px solid var(--line)}
.acct-tabs a{padding:10px 16px;font-weight:600;color:var(--ink2);text-decoration:none;border-bottom:3px solid transparent;margin-bottom:-1px}
.acct-tabs a.on{color:var(--brand-ink);border-color:var(--brand)}
.kpis{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-bottom:22px}
.kpi{background:var(--surface);border:1px solid var(--line);border-radius:14px;padding:14px 16px}
.kpi span{display:block;font-size:.8rem;color:var(--ink3);font-weight:600}.kpi b{display:block;font-size:1.4rem;font-stretch:85%;margin:4px 0 2px}.kpi small{color:var(--ink3);font-size:.76rem}
.kpi.bad{border-color:color-mix(in srgb,var(--out) 40%,transparent)}.kpi.bad b{color:var(--out)}.kpi.warn{border-color:color-mix(in srgb,var(--low) 40%,transparent)}.kpi.warn b{color:var(--low)}
.sec-h{display:flex;justify-content:space-between;align-items:baseline;margin:22px 0 10px}.sec-h h2{font-size:1.1rem;margin:0}.sec-h a{font-size:.88rem;color:var(--brand-ink);font-weight:600;text-decoration:none}
.chips{display:flex;gap:8px;margin-bottom:14px}.chips a{padding:7px 14px;border-radius:99px;border:1px solid var(--line);text-decoration:none;color:var(--ink2);font-weight:600;font-size:.88rem}.chips a.on{background:var(--wash2);color:var(--brand-ink);border-color:var(--edge)}
.tag.ok{background:var(--okbg);color:var(--ok)}.tag.bad{background:var(--outbg);color:var(--out)}.tag.warn{background:var(--lowbg);color:var(--low)}
a.mono,.mono{font-family:var(--mono);font-weight:700;font-size:.88rem;color:var(--brand-ink);text-decoration:none}
tr.clk{cursor:pointer}tr.clk:hover td{background:var(--wash)}
@media (max-width:760px){.kpis{grid-template-columns:1fr 1fr}.tl .st{font-size:.74rem}.tl .st small{display:none}.acct-tabs a{padding:9px 10px;font-size:.88rem}}
/* ─────────── tableau de bord accueil ─────────── */
.dash{margin:18px 0 8px}
.dash-act{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px}
.dbtn{display:inline-flex;align-items:center;gap:8px;height:42px;padding:0 15px;border-radius:12px;border:1px solid var(--line);background:var(--surface);color:var(--ink);font-weight:600;font-size:.9rem;text-decoration:none;cursor:pointer;transition:transform .15s,border-color .15s,background .15s}
.dbtn:hover{border-color:var(--edge);background:var(--wash);transform:translateY(-1px)}.dbtn.pri{background:var(--brand);border-color:var(--brand);color:#fff}.dbtn.pri:hover{background:var(--brand-ink)}
.dbtn b{background:var(--wash2);color:var(--brand-ink);border-radius:99px;padding:0 8px;font-size:.78rem;line-height:20px}.dbtn.pri b{background:rgba(255,255,255,.22);color:#fff}.dbtn b.bad{background:var(--outbg);color:var(--out)}
.dbtn[disabled]{opacity:.6;cursor:wait}
.dash-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.dash-card{background:var(--surface);border:1px solid var(--line);border-radius:16px;padding:14px 16px}
.dc-h{display:flex;justify-content:space-between;align-items:baseline;font-weight:700;margin-bottom:10px}.dc-h a{font-size:.82rem;color:var(--brand-ink);text-decoration:none;font-weight:600}
.dc-kpis{display:grid;grid-template-columns:repeat(3,1fr);gap:8px}.dc-kpis span{display:block;font-size:.74rem;color:var(--ink3);font-weight:600}.dc-kpis b{display:block;font-size:1.15rem;font-stretch:85%;margin-top:2px}.dc-kpis small{font-size:.72rem;color:var(--ink3)}
.dc-kpis .bad b,.dc-kpis .bad small{color:var(--out)}.dc-kpis .warn b{color:var(--low)}
.dc-o{display:grid;grid-template-columns:auto 1fr auto auto;gap:10px;align-items:center;padding:8px 0;border-top:1px solid var(--line2);text-decoration:none;color:var(--ink);font-size:.88rem}.dc-o:first-of-type{border-top:0}
.dc-o .n{font:700 .84rem var(--mono)}.dc-o .d{color:var(--ink3);font-size:.8rem}.dc-o .t{font-weight:700}.dc-o .os{font-size:.72rem;padding:3px 9px}
.dc-e{color:var(--ink3);font-size:.88rem;padding:6px 0}
@media (max-width:760px){.dash-grid{grid-template-columns:1fr}.dbtn{flex:1 1 calc(50% - 8px);justify-content:center;padding:0 10px}.dc-o{grid-template-columns:1fr auto}.dc-o .d{display:none}.dc-o .os{grid-column:1/-1;justify-self:start}}
/* ─────────── indicateurs de chargement ─────────── */
.spin{display:inline-block;width:20px;height:20px;border-radius:50%;border:3px solid var(--wash2);border-top-color:var(--brand);border-right-color:var(--brand);animation:spin .7s linear infinite;flex:none}
.spin.lg{width:26px;height:26px;border-width:3px}.spin.sm{width:16px;height:16px;border-width:2.5px}
@keyframes spin{to{transform:rotate(360deg)}}
.busy{position:fixed;top:84px;left:50%;transform:translate(-50%,-14px);display:flex;align-items:center;gap:12px;background:var(--surface);color:var(--ink);border:1px solid var(--line);border-radius:99px;padding:10px 20px 10px 14px;font-weight:600;font-size:.92rem;box-shadow:var(--shadow,0 12px 36px rgba(17,22,29,.18));z-index:60;opacity:0;pointer-events:none;transition:opacity .15s,transform .2s}
.busy.on{opacity:1;transform:translate(-50%,0)}
.omni-box.is-busy>.ic{display:none}
@media (prefers-reduced-motion:reduce){.spin{animation-duration:1.6s}}
.pdfb{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:8px;border:1px solid var(--line);background:var(--surface);color:var(--brand-ink);font-weight:700;font-size:.78rem;text-decoration:none}.pdfb:hover{border-color:var(--edge);background:var(--wash)}
</style>
<style>
/* demandes de devis */
.nav2{min-width:0}.nav2 a{white-space:nowrap}@media (max-width:760px){.nav2{justify-self:stretch;overflow-x:auto;scrollbar-width:none}.nav2::-webkit-scrollbar{display:none}.nav2 a{padding:8px 9px;font-size:.88rem}}
.os.st-new,.os.st-processing{background:var(--lowbg);color:var(--low)}.os.st-answered{background:var(--wash2);color:var(--brand-ink)}.os.st-accepted{background:var(--okbg);color:var(--ok)}.os.st-declined{background:var(--outbg);color:var(--out)}
.qform .sec{background:var(--surface);border:1px solid var(--line);border-radius:14px;padding:16px 18px;margin-top:14px}
.qform .sec h2{font-size:1rem;margin:0 0 4px}.qform .sec .hint{font-size:.84rem;color:var(--ink3);margin:0 0 12px}
.qform label.l{display:block;font-size:.82rem;font-weight:600;margin:10px 0 5px}
.qform input[type=text],.qform input[type=number],.qform select,.qform textarea{width:100%;border:1.5px solid var(--line);border-radius:11px;padding:10px 12px;background:var(--ground);outline:0}
.qform input:focus,.qform select:focus,.qform textarea:focus{border-color:var(--brand);background:var(--surface)}
.qform .two{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.qrow{display:grid;grid-template-columns:150px minmax(0,1fr) 140px 84px 38px;gap:8px;align-items:center;margin-bottom:8px}
.qrow .rm{height:42px;border:1px solid var(--line);background:var(--surface);border-radius:10px;font-size:1.1rem;color:var(--ink3)}.qrow .rm:hover{color:var(--out);border-color:var(--out)}
.qhead{display:grid;grid-template-columns:150px minmax(0,1fr) 140px 84px 38px;gap:8px;font-size:.76rem;color:var(--ink3);font-weight:600;margin-bottom:6px}
.qform .errs{margin-top:14px}
.avl{display:inline-block;border-radius:6px;padding:1px 8px;font-size:.76rem;font-weight:700}.avl.stock{background:var(--okbg);color:var(--ok)}.avl.delay{background:var(--lowbg);color:var(--low)}.avl.unavailable{background:var(--outbg);color:var(--out)}
.qtot{display:flex;justify-content:flex-end;gap:22px;padding:12px 14px;border-top:1px solid var(--line2);font-size:.92rem}.qtot b{font-size:1.05rem}
.qact{display:flex;gap:10px;flex-wrap:wrap;margin-top:14px}
@media (max-width:760px){.qform .two{grid-template-columns:1fr}.qhead{display:none}.qrow{grid-template-columns:1fr 84px 38px;grid-template-areas:"a a a" "b b b" "c d e"}.qrow>:nth-child(1){grid-area:a}.qrow>:nth-child(2){grid-area:b}.qrow>:nth-child(3){grid-area:c}.qrow>:nth-child(4){grid-area:d}.qrow>:nth-child(5){grid-area:e}}
</style>
<style>
.os.st-negotiating{background:var(--lowbg);color:var(--low)}
.evl{margin-top:10px}.ev{display:flex;gap:12px;padding:8px 0;border-top:1px solid var(--line2);font-size:.9rem}.ev:first-child{border-top:0}
.ev .dot{flex:0 0 10px;height:10px;border-radius:50%;background:var(--line);margin-top:6px}.ev.vendor .dot{background:var(--brand)}.ev.client .dot{background:var(--ok)}
.qn{display:flex;gap:10px;align-items:center;flex-wrap:wrap;background:var(--wash2);border-radius:12px;padding:10px 14px;margin:10px 0;font-size:.92rem}.qn b{color:var(--brand-ink)}.qn a{margin-left:auto;font-weight:700;color:var(--brand-ink);text-decoration:none}.plate-row{display:flex;gap:8px;max-width:360px}.plate-row input{flex:1;min-width:0;height:44px;border:2px solid #1f2937;border-radius:9px;text-align:center;font:800 1.1rem var(--mono);letter-spacing:.1em;text-transform:uppercase;background:var(--surface);color:var(--ink)}.plate-row .btn2{height:44px}.plate-res{min-height:22px;margin:6px 0 10px;font-size:.88rem;color:var(--ink3)}.plate-res.ok{color:var(--ok);font-weight:700}.plate-res.bad{color:var(--low)}
.drop{display:flex;gap:14px;align-items:center;border:2px dashed var(--line);border-radius:14px;padding:16px 18px;cursor:pointer;background:var(--surface)}.drop:hover,.drop.on{border-color:var(--brand);background:var(--wash2)}.drop small{display:block;color:var(--ink3);font-size:.8rem;font-weight:400}.drop .ic{width:26px;height:26px;flex:0 0 auto;stroke:var(--brand-ink);fill:none;stroke-width:2}
.fprev{display:flex;flex-wrap:wrap;gap:10px;margin-top:10px}.fp{position:relative;width:84px;height:84px;border-radius:10px;border:1px solid var(--line);overflow:hidden;background:var(--wash2);display:flex;align-items:center;justify-content:center;font-weight:800;color:var(--low)}.fp img{width:100%;height:100%;object-fit:cover}.fp button{position:absolute;top:3px;right:3px;width:22px;height:22px;border-radius:50%;border:0;background:rgba(17,22,29,.75);color:#fff;font-size:.95rem;line-height:20px;cursor:pointer;padding:0}
.qfiles{display:flex;flex-wrap:wrap;gap:10px;margin-top:8px}.qfile{display:flex;flex-direction:column;align-items:center;gap:4px;width:96px;font-size:.74rem;text-align:center;color:var(--ink2);text-decoration:none}.qfile .th{width:96px;height:72px;border-radius:10px;border:1px solid var(--line);background:var(--wash2) center/cover no-repeat;display:flex;align-items:center;justify-content:center;font-weight:800;color:var(--low)}.qfile.v .th{border-color:var(--brand)}
.qkeep{width:20px;height:20px;accent-color:var(--brand)}tr.refused td{opacity:.5}tr.refused td:first-child b{text-decoration:line-through}.qsel{margin-top:10px;padding:10px 14px;background:var(--wash2);border-radius:12px;font-size:.92rem}.qsel b{color:var(--brand-ink)}
.qn.promo{background:#fde7e9}.qn.promo b,.qn.promo a{color:#b3202e}.dbtn.promo{border-color:#f3b8bd;color:#b3202e}.dbtn.promo b{background:#b3202e;color:#fff}
.nav2 a .nb{display:inline-block;min-width:18px;padding:0 6px;margin-left:6px;border-radius:99px;background:var(--out);color:#fff;font-size:.72rem;line-height:18px;text-align:center}
/* ─────────── tableau de bord : atelier ─────────── */
.hero{position:relative;overflow:hidden;border-radius:22px;padding:30px 34px 64px;color:#fff;background:linear-gradient(128deg,var(--brand-ink) 0%,var(--brand) 62%,color-mix(in srgb,var(--brand) 82%,#fff) 100%);isolation:isolate}
.hero::before{content:"";position:absolute;inset:0;z-index:-1;background:repeating-linear-gradient(115deg,rgba(255,255,255,.045) 0 1.5px,transparent 1.5px 9px);mask-image:linear-gradient(90deg,#000 20%,transparent 85%);-webkit-mask-image:linear-gradient(90deg,#000 20%,transparent 85%)}
.hero-txt{max-width:560px;position:relative}
.hero-day{margin:0 0 6px;font-size:.92rem;font-weight:600;color:rgba(255,255,255,.72);letter-spacing:.01em}
.home .hero h1{background:none;-webkit-text-fill-color:#fff;color:#fff;font-size:clamp(2.2rem,5vw,3.3rem);margin:0 0 8px;font-stretch:72%;letter-spacing:-.015em;line-height:1}
.home .hero .sub{color:rgba(255,255,255,.82);margin:0;font-size:1.02rem;max-width:46ch}.hero .sub b{color:#fff;font-family:var(--mono);font-weight:700}
.hero-disc{position:absolute;right:-34px;top:50%;width:clamp(190px,30vw,330px);height:auto;transform:translateY(-52%);fill:none;stroke:rgba(255,255,255,.5);stroke-width:1.6;z-index:-1}
.hero-disc .disc{transform-origin:120px 120px;animation:discspin 2.4s cubic-bezier(.08,.62,.18,1) both}
.hero-disc .slots line{stroke:rgba(255,255,255,.34);stroke-width:5;stroke-linecap:round}
.hero-disc .ring{stroke:rgba(255,255,255,.28)}.hero-disc .hub{fill:rgba(255,255,255,.09);stroke:rgba(255,255,255,.55)}.hero-disc .cap{fill:rgba(255,255,255,.2);stroke:rgba(255,255,255,.6)}.hero-disc .bolts circle{fill:rgba(0,0,0,.28);stroke:rgba(255,255,255,.5);stroke-width:1.2}
.hero-disc .caliper{fill:#F5B301;stroke:none;opacity:.95;filter:drop-shadow(0 4px 10px rgba(0,0,0,.25))}
@keyframes discspin{from{transform:rotate(-620deg)}to{transform:none}}
.home .quick{margin:-38px 14px 6px;position:relative;z-index:1}
.home .qa{box-shadow:0 10px 30px rgba(17,22,29,.12);border-color:transparent}
.sect-a{margin-left:auto;font-size:.84rem;font-weight:600;text-decoration:none}
.todo{display:grid;grid-template-columns:repeat(auto-fill,minmax(290px,1fr));gap:12px;margin-bottom:14px}
.tk{--tc:var(--brand);position:relative;display:grid;grid-template-columns:64px minmax(0,1fr) auto;align-items:center;gap:0;min-height:84px;padding:0 16px 0 0;text-align:left;text-decoration:none;color:var(--ink);background:var(--surface);border:1px solid var(--line);border-radius:16px;overflow:visible;transition:transform .2s,box-shadow .2s,border-color .2s}
button.tk{width:100%;font:inherit}
.tk:hover{transform:translateY(-3px);box-shadow:0 14px 30px rgba(17,22,29,.12);border-color:color-mix(in srgb,var(--tc) 40%,var(--line))}
.tk-stub{position:relative;align-self:stretch;display:grid;place-items:center;color:var(--tc);background:color-mix(in srgb,var(--tc) 11%,var(--surface));border-radius:15px 0 0 15px;border-right:2px dashed color-mix(in srgb,var(--tc) 38%,var(--surface))}
.tk-stub::before,.tk-stub::after{content:"";position:absolute;right:-8px;width:14px;height:14px;border-radius:50%;background:var(--ground);border:1px solid var(--line);z-index:1}
.tk-stub::before{top:-8px;clip-path:inset(50% 0 0 0)}.tk-stub::after{bottom:-8px;clip-path:inset(0 0 50% 0)}
.tk-stub .ic{width:24px;height:24px}
.tk-dot{position:absolute;top:12px;left:12px;width:9px;height:9px;border-radius:50%;background:#F5B301;box-shadow:0 0 0 0 rgba(245,179,1,.6);animation:tkping 1.6s ease-out 3}
@keyframes tkping{70%{box-shadow:0 0 0 9px rgba(245,179,1,0)}100%{box-shadow:0 0 0 0 rgba(245,179,1,0)}}
.tk-body{padding:12px 12px 12px 18px;min-width:0;display:flex;flex-direction:column;gap:1px}
.tk-body small{font-size:.76rem;font-weight:600;color:var(--tc)}.tk-body b{font-size:1.02rem;font-stretch:90%;line-height:1.25;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}.tk-body em{font-style:normal;font-size:.84rem;color:var(--ink3);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.tk-go{color:var(--ink3);transition:transform .2s,color .2s}.tk:hover .tk-go{transform:translateX(4px);color:var(--tc)}
.tk.t-ok{--tc:var(--ok)}.tk.t-promo{--tc:#C0283A}.tk.t-warn{--tc:var(--low)}.tk.t-info{--tc:var(--ink2)}.tk.t-calm{--tc:var(--ok);grid-column:1/-1;box-shadow:none}.tk.t-calm:hover{transform:none;box-shadow:none}
.kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:12px;margin-bottom:6px}
.kpi{display:flex;flex-direction:column;gap:2px;padding:14px 18px;background:var(--surface);border:1px solid var(--line);border-radius:16px;text-decoration:none;color:var(--ink);border-bottom:3px solid var(--line);transition:transform .2s,border-color .2s}
.kpi:hover{transform:translateY(-2px);border-bottom-color:var(--brand)}.kpi span{font-size:.8rem;font-weight:600;color:var(--ink3)}.kpi b{font:800 1.7rem/1.1 var(--mono);letter-spacing:-.02em}
.kpi.on{border-bottom-color:var(--ok)}.kpi.on b{color:var(--ok)}.kpi.bad{border-bottom-color:var(--out)}.kpi.bad b{color:var(--out)}
.prail{display:flex;gap:12px;overflow-x:auto;scroll-snap-type:x proximity;padding:4px 2px 12px;margin:0 -2px;scrollbar-width:thin}
.pc{position:relative;flex:0 0 232px;scroll-snap-align:start;display:flex;flex-direction:column;gap:4px;padding:16px 16px 14px;background:var(--surface);border:1px solid var(--line);border-radius:16px;text-decoration:none;color:var(--ink);overflow:hidden;transition:transform .2s,box-shadow .2s,border-color .2s}
.pc::after{content:"";position:absolute;right:-30px;top:-30px;width:104px;height:104px;border-radius:50%;background:radial-gradient(circle at 30% 30%,#E5384C,#B3202E);opacity:.95}
.pc:hover{transform:translateY(-3px);box-shadow:0 14px 30px rgba(17,22,29,.12);border-color:#e9a8b0}
.pc-tag{position:absolute;top:9px;right:8px;z-index:1;color:#fff;font:800 .76rem var(--mono);transform:rotate(18deg);white-space:nowrap}
.pc-code{font:600 .76rem var(--mono);color:var(--ink3);padding-right:52px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.pc-name{padding-right:40px;font-weight:600;font-size:.94rem;line-height:1.3;min-height:2.6em;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.pc-price{display:flex;align-items:baseline;gap:8px;margin-top:6px}.pc-price b{font:800 1.45rem var(--mono);letter-spacing:-.02em;color:#B3202E}.pc-price s{color:var(--ink3);font-size:.88rem}.pc-end{color:var(--ink3);font-size:.76rem}
@media (max-width:640px){.hero{padding:22px 20px 56px;border-radius:18px}.hero-disc{opacity:.5;right:-70px;width:210px}.home .quick{margin:-34px 4px 6px}.todo{grid-template-columns:1fr}.kpis{grid-template-columns:1fr 1fr}.pc{flex-basis:208px}}
@media (prefers-color-scheme:dark){.hero{background:linear-gradient(128deg,color-mix(in srgb,var(--brand) 55%,#000),var(--brand-ink))}.tk-stub::before,.tk-stub::after{background:var(--ground)}}
.hero-cta{display:flex;flex-wrap:wrap;align-items:center;gap:12px 18px;margin-top:22px}
.cta-main{display:inline-flex;align-items:center;gap:14px;padding:13px 22px 13px 16px;border-radius:16px;background:#F5B301;color:#1B1405;text-decoration:none;box-shadow:0 10px 26px rgba(0,0,0,.28),inset 0 -3px 0 rgba(0,0,0,.12);transition:transform .2s,box-shadow .2s}
.cta-main:hover{transform:translateY(-2px);box-shadow:0 16px 32px rgba(0,0,0,.32),inset 0 -3px 0 rgba(0,0,0,.12)}
.cta-main .ic{width:30px;height:30px;padding:6px;border-radius:10px;background:rgba(27,20,5,.12);box-sizing:content-box}
.cta-main span{display:flex;flex-direction:column;line-height:1.2}.cta-main b{font-size:1.08rem;font-stretch:90%}.cta-main small{font-size:.8rem;font-weight:500;opacity:.78}
.cta-sec{display:inline-flex;align-items:center;gap:8px;color:#fff;font-weight:600;text-decoration:none;padding:10px 4px;border-bottom:2px solid rgba(255,255,255,.35);transition:border-color .2s}.cta-sec:hover{border-color:#fff}
.hero-txt{max-width:600px}
.hero{padding:20px 30px 56px}
.home .hero h1{font-size:clamp(1.9rem,4vw,2.5rem)!important}.hero-cta{margin-top:14px}.cta-main{padding:9px 18px 9px 12px}.cta-main .ic{width:22px;height:22px;padding:5px}.hero-disc{width:clamp(150px,22vw,240px)}.cta-sec{background:none;border:0;border-bottom:2px solid rgba(255,255,255,.35);font:inherit;font-weight:600;cursor:pointer}
.home .sect{display:flex;align-items:baseline;flex-wrap:wrap;gap:4px 12px;margin:30px 0 12px;font-size:1.12rem}
.home .sect::before{content:"";align-self:center;width:5px;height:20px;border-radius:3px;background:var(--brand)}
.home .sect small{font-size:.82rem}
.dash-act{display:grid;grid-template-columns:repeat(auto-fill,minmax(210px,1fr));gap:10px}
.dash-act .dbtn{justify-content:flex-start;min-height:48px;border-radius:14px}
.pchips{display:flex;gap:10px;overflow-x:auto;margin:16px 0 0;padding-bottom:6px;scroll-snap-type:x proximity}
.pchips a{flex:0 0 auto;scroll-snap-align:start;display:flex;flex-direction:column;gap:2px;padding:10px 16px;border:1px solid var(--line);border-radius:14px;background:var(--surface);text-decoration:none;color:var(--ink);border-left:4px solid #C0283A;transition:transform .2s,box-shadow .2s}
.pchips a:hover{transform:translateY(-2px);box-shadow:0 10px 22px rgba(17,22,29,.1)}.pchips b{font-size:.94rem}.pchips small{color:var(--ink3);font-size:.78rem}
.pc-end{white-space:normal;line-height:1.3}

.phid{margin-top:10px;padding:12px 16px;border:1px dashed var(--line);border-radius:14px;color:var(--ink3);font-size:.84rem;display:flex;flex-direction:column;gap:3px}.phid b{color:var(--ink2);font-size:.86rem}.phid code{font-family:var(--mono);color:var(--ink2)}
@media (max-width:640px){.cta-main{width:100%;justify-content:flex-start}.hero{padding:18px 18px 52px}.dash-act{grid-template-columns:1fr 1fr}}
@media (prefers-reduced-motion:reduce){.hero-disc .disc,.tk-dot{animation:none!important}.tk:hover,.pc:hover,.kpi:hover{transform:none}}
</style>
