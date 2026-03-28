@extends('frontend.layout')

@php
    $title = __('Politique de Confidentialite');
@endphp

<x-meta-tags :meta-tag-content="$seoInfo" :page-heading="$title" />

@section('style')
<style>
    .privacy-wrap {
        padding: 80px 0 100px;
        background: var(--bg-2);
    }

    .privacy-container {
        max-width: 980px;
        margin: 0 auto;
    }

    .privacy-intro {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 32px;
        margin-bottom: 16px;
    }

    .privacy-intro h2 {
        font-size: 30px;
        margin-bottom: 12px;
    }

    .privacy-intro p {
        font-size: 15px;
        line-height: 1.85;
        color: var(--color-medium);
        margin-bottom: 10px;
    }

    .privacy-summary {
        margin-top: 14px;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    @media (max-width: 767.98px) {
        .privacy-intro {
            padding: 22px 18px;
        }

        .privacy-intro h2 {
            font-size: 24px;
        }

        .privacy-summary {
            grid-template-columns: 1fr;
        }
    }

    .privacy-summary div {
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: var(--bg-2);
        padding: 12px 14px;
        font-size: 13.5px;
        color: var(--color-medium);
    }

    .privacy-toc {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 20px 22px;
        margin-bottom: 16px;
    }

    .privacy-toc h4 {
        font-size: 13px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .privacy-toc ul {
        margin: 0;
        padding: 0;
        list-style: none;
        columns: 2;
        column-gap: 24px;
    }

    .privacy-toc li {
        break-inside: avoid;
        margin-bottom: 8px;
    }

    .privacy-toc a {
        display: inline-flex;
        gap: 8px;
        font-size: 13.5px;
        color: var(--color-medium);
        text-decoration: none;
        line-height: 1.5;
    }

    .privacy-toc a:hover,
    .privacy-toc a.active {
        color: var(--color-primary);
        font-weight: 600;
    }

    @media (max-width: 767.98px) {
        .privacy-toc ul {
            columns: 1;
        }
    }

    .privacy-section {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 30px 32px;
        margin-bottom: 14px;
        scroll-margin-top: 110px;
    }

    @media (max-width: 767.98px) {
        .privacy-section {
            padding: 20px 18px;
        }
    }

    .privacy-section .sec-number {
        font-size: 11px;
        letter-spacing: .1em;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--color-primary);
        margin-bottom: 6px;
    }

    .privacy-section h3 {
        font-size: 22px;
        margin-bottom: 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border-color);
    }

    .privacy-section p {
        font-size: 15px;
        line-height: 1.85;
        color: var(--color-medium);
        margin-bottom: 10px;
    }

    .privacy-list {
        margin: 10px 0 0;
        padding-left: 18px;
    }

    .privacy-list li {
        font-size: 15px;
        line-height: 1.8;
        color: var(--color-medium);
        margin-bottom: 4px;
    }

    .privacy-subgrid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 12px;
        margin-top: 10px;
    }

    .privacy-subcard {
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background: var(--bg-2);
        padding: 14px;
    }

    .privacy-subcard h5 {
        font-size: 14px;
        margin-bottom: 6px;
    }

    .privacy-subcard ul {
        margin: 0;
        padding-left: 16px;
    }

    .privacy-subcard li {
        font-size: 13.5px;
        line-height: 1.7;
        color: var(--color-medium);
    }

    .privacy-alert {
        margin-top: 12px;
        padding: 12px 14px;
        border: 1px solid #fde68a;
        background: #fffbeb;
        color: #92400e;
        border-radius: var(--radius-sm);
        font-size: 13.5px;
        line-height: 1.6;
    }

    .privacy-cta {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 28px 24px;
        text-align: center;
    }

    .privacy-cta h4 {
        font-size: 22px;
        margin-bottom: 8px;
    }

    .privacy-cta p {
        max-width: 680px;
        margin: 0 auto 16px;
        color: var(--color-medium);
        line-height: 1.75;
    }

    .breadcrumb-area {
        min-height: 220px;
        display: flex;
        align-items: center;
    }
</style>
@endsection

@section('content')
@includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb ?? '', 'title' => $title])

<section class="privacy-wrap">
    <div class="container">
        <div class="privacy-container" data-aos="fade-up">

            <div class="privacy-intro">
                <h2>{{ __('Politique de Confidentialite') }}</h2>
                <p>{{ __('Chez SPACEHUB, la protection de vos donnees personnelles est une priorite absolue. Nous nous engageons a garantir la securite, la confidentialite et la transparence dans le traitement de vos informations.') }}</p>
                <p>{{ __('Cette politique presente les donnees collectees, leurs finalites, les mesures de protection mises en place ainsi que vos droits.') }}</p>
                <div class="privacy-summary">
                    <div>{{ __('Collecte responsable des donnees strictement necessaires au service.') }}</div>
                    <div>{{ __('Traitement transparent et conforme aux obligations legales applicables.') }}</div>
                    <div>{{ __('Mesures de securite techniques et organisationnelles renforcees.') }}</div>
                    <div>{{ __('Respect de vos droits : acces, rectification, opposition, suppression.') }}</div>
                </div>
            </div>

            <nav class="privacy-toc">
                <h4>{{ __('Sommaire') }}</h4>
                <ul>
                    <li><a href="#s1"><span>1.</span><span>{{ __('Introduction') }}</span></a></li>
                    <li><a href="#s2"><span>2.</span><span>{{ __('Qui sommes-nous ?') }}</span></a></li>
                    <li><a href="#s3"><span>3.</span><span>{{ __('Donnees collectees') }}</span></a></li>
                    <li><a href="#s4"><span>4.</span><span>{{ __('Utilisation des donnees') }}</span></a></li>
                    <li><a href="#s5"><span>5.</span><span>{{ __('Partage des donnees') }}</span></a></li>
                    <li><a href="#s6"><span>6.</span><span>{{ __('Securite des donnees') }}</span></a></li>
                    <li><a href="#s7"><span>7.</span><span>{{ __('Conservation des donnees') }}</span></a></li>
                    <li><a href="#s8"><span>8.</span><span>{{ __('Vos droits') }}</span></a></li>
                    <li><a href="#s9"><span>9.</span><span>{{ __('Cookies et technologies similaires') }}</span></a></li>
                    <li><a href="#s10"><span>10.</span><span>{{ __('Specificites africaines') }}</span></a></li>
                    <li><a href="#s11"><span>11.</span><span>{{ __('Donnees des mineurs') }}</span></a></li>
                    <li><a href="#s12"><span>12.</span><span>{{ __('Modifications de la politique') }}</span></a></li>
                </ul>
            </nav>

            <article class="privacy-section" id="s1">
                <div class="sec-number">Article 01</div>
                <h3>{{ __('Introduction') }}</h3>
                <p>{{ __('La presente Politique de Confidentialite explique quelles donnees nous collectons, comment nous les utilisons, comment nous les protegeons et quels sont vos droits.') }}</p>
                <p>{{ __('En utilisant notre plateforme, vous acceptez les pratiques decrites dans le present document.') }}</p>
            </article>

            <article class="privacy-section" id="s2">
                <div class="sec-number">Article 02</div>
                <h3>{{ __('Qui sommes-nous ?') }}</h3>
                <p>{{ __('SPACEHUB est une plateforme digitale permettant la reservation d\'espaces, la mise en relation entre proprietaires d\'espaces et utilisateurs, ainsi que la gestion des services associes.') }}</p>
            </article>

            <article class="privacy-section" id="s3">
                <div class="sec-number">Article 03</div>
                <h3>{{ __('Donnees collectees') }}</h3>
                <p>{{ __('Nous collectons differentes categories de donnees afin d\'assurer le bon fonctionnement de la plateforme.') }}</p>
                <div class="privacy-subgrid">
                    <div class="privacy-subcard">
                        <h5>{{ __('Donnees personnelles') }}</h5>
                        <ul>
                            <li>{{ __('Nom et prenom') }}</li>
                            <li>{{ __('Numero de telephone') }}</li>
                            <li>{{ __('Adresse e-mail') }}</li>
                            <li>{{ __('Photo de profil (optionnelle)') }}</li>
                        </ul>
                    </div>
                    <div class="privacy-subcard">
                        <h5>{{ __('Donnees professionnelles') }}</h5>
                        <ul>
                            <li>{{ __('Nom de l\'entreprise ou etablissement') }}</li>
                            <li>{{ __('Adresse de l\'espace') }}</li>
                            <li>{{ __('Informations de facturation') }}</li>
                            <li>{{ __('Numero d\'identification (si applicable)') }}</li>
                        </ul>
                    </div>
                    <div class="privacy-subcard">
                        <h5>{{ __('Donnees de reservation') }}</h5>
                        <ul>
                            <li>{{ __('Details des reservations') }}</li>
                            <li>{{ __('Historique des transactions') }}</li>
                            <li>{{ __('Preferences utilisateur') }}</li>
                        </ul>
                    </div>
                    <div class="privacy-subcard">
                        <h5>{{ __('Donnees techniques') }}</h5>
                        <ul>
                            <li>{{ __('Adresse IP') }}</li>
                            <li>{{ __('Type d\'appareil') }}</li>
                            <li>{{ __('Navigateur utilise') }}</li>
                            <li>{{ __('Donnees de navigation') }}</li>
                        </ul>
                    </div>
                    <div class="privacy-subcard">
                        <h5>{{ __('Donnees de paiement') }}</h5>
                        <ul>
                            <li>{{ __('Informations liees aux transactions (Mobile Money, cartes, etc.)') }}</li>
                        </ul>
                    </div>
                </div>
                <div class="privacy-alert">{{ __('Nous ne stockons pas vos donnees bancaires completes.') }}</div>
            </article>

            <article class="privacy-section" id="s4">
                <div class="sec-number">Article 04</div>
                <h3>{{ __('Utilisation des donnees') }}</h3>
                <ul class="privacy-list">
                    <li>{{ __('Fournir et ameliorer nos services') }}</li>
                    <li>{{ __('Gerer les reservations') }}</li>
                    <li>{{ __('Faciliter les paiements') }}</li>
                    <li>{{ __('Assurer la securite de la plateforme') }}</li>
                    <li>{{ __('Communiquer avec vous (SMS, WhatsApp, e-mail)') }}</li>
                    <li>{{ __('Personnaliser votre experience utilisateur') }}</li>
                    <li>{{ __('Respecter les obligations legales') }}</li>
                </ul>
            </article>

            <article class="privacy-section" id="s5">
                <div class="sec-number">Article 05</div>
                <h3>{{ __('Partage des donnees') }}</h3>
                <p>{{ __('Nous ne vendons jamais vos donnees. Certaines informations peuvent etre partagees uniquement lorsque cela est necessaire.') }}</p>
                <ul class="privacy-list">
                    <li>{{ __('Partenaires techniques : services de paiement, hebergeurs, cloud') }}</li>
                    <li>{{ __('Prestataires de services : support client, outils e-mail et SMS') }}</li>
                    <li>{{ __('Autorites competentes : en cas d\'obligation legale ou judiciaire') }}</li>
                </ul>
            </article>

            <article class="privacy-section" id="s6">
                <div class="sec-number">Article 06</div>
                <h3>{{ __('Securite des donnees') }}</h3>
                <ul class="privacy-list">
                    <li>{{ __('Chiffrement des donnees sensibles') }}</li>
                    <li>{{ __('Acces securise aux comptes') }}</li>
                    <li>{{ __('Surveillance des activites suspectes') }}</li>
                    <li>{{ __('Hebergement securise') }}</li>
                </ul>
                <div class="privacy-alert">{{ __('Malgre nos efforts, aucun systeme n\'est totalement securise.') }}</div>
            </article>

            <article class="privacy-section" id="s7">
                <div class="sec-number">Article 07</div>
                <h3>{{ __('Conservation des donnees') }}</h3>
                <ul class="privacy-list">
                    <li>{{ __('Aussi longtemps que necessaire pour fournir nos services') }}</li>
                    <li>{{ __('Pour respecter nos obligations legales') }}</li>
                    <li>{{ __('Ou jusqu\'a suppression de votre compte') }}</li>
                </ul>
            </article>

            <article class="privacy-section" id="s8">
                <div class="sec-number">Article 08</div>
                <h3>{{ __('Vos droits') }}</h3>
                <p>{{ __('Conformement aux bonnes pratiques internationales, vous avez le droit de :') }}</p>
                <ul class="privacy-list">
                    <li>{{ __('Acceder a vos donnees') }}</li>
                    <li>{{ __('Modifier vos informations') }}</li>
                    <li>{{ __('Demander la suppression de vos donnees') }}</li>
                    <li>{{ __('Vous opposer a certains traitements') }}</li>
                    <li>{{ __('Retirer votre consentement') }}</li>
                </ul>
                <p>{{ __('Pour exercer vos droits, contactez-nous via la plateforme.') }}</p>
            </article>

            <article class="privacy-section" id="s9">
                <div class="sec-number">Article 09</div>
                <h3>{{ __('Cookies et technologies similaires') }}</h3>
                <p>{{ __('Nous utilisons des cookies pour ameliorer la navigation, analyser l\'utilisation de la plateforme et personnaliser le contenu.') }}</p>
                <p>{{ __('Vous pouvez configurer votre navigateur pour refuser les cookies.') }}</p>
            </article>

            <article class="privacy-section" id="s10">
                <div class="sec-number">Article 10</div>
                <h3>{{ __('Specificites africaines') }}</h3>
                <p>{{ __('Dans le contexte africain et particulierement en RDC :') }}</p>
                <ul class="privacy-list">
                    <li>{{ __('Integration de solutions de paiement locales (Mobile Money)') }}</li>
                    <li>{{ __('Collecte de donnees adaptee a la realite du marche informel') }}</li>
                    <li>{{ __('Exigences administratives limitees pour favoriser l\'inclusion') }}</li>
                </ul>
            </article>

            <article class="privacy-section" id="s11">
                <div class="sec-number">Article 11</div>
                <h3>{{ __('Donnees des mineurs') }}</h3>
                <p>{{ __('Nos services ne sont pas destines aux personnes de moins de 18 ans. Nous ne collectons pas volontairement leurs donnees.') }}</p>
            </article>

            <article class="privacy-section" id="s12">
                <div class="sec-number">Article 12</div>
                <h3>{{ __('Modifications de la politique') }}</h3>
                <p>{{ __('Cette politique peut etre mise a jour a tout moment.') }}</p>
                <p>{{ __('Les utilisateurs seront informes en cas de changement important.') }}</p>
            </article>

            <section class="privacy-cta">
                <h4>{{ __('Besoin d\'assistance ?') }}</h4>
                <p>{{ __('Pour toute question relative a la confidentialite et a vos donnees personnelles, contactez notre equipe via le formulaire de contact.') }}</p>
                <a href="{{ route('contact') }}" class="btn btn-lg btn-primary">{{ __('Contacter le support') }}</a>
            </section>

        </div>
    </div>
</section>
@endsection

@section('script')
<script>
(function () {
    var sections = document.querySelectorAll('.privacy-section[id]');
    var links = document.querySelectorAll('.privacy-toc a');
    if (!sections.length || !links.length) return;

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                links.forEach(function (link) { link.classList.remove('active'); });
                var active = document.querySelector('.privacy-toc a[href="#' + entry.target.id + '"]');
                if (active) active.classList.add('active');
            }
        });
    }, { rootMargin: '-25% 0px -65% 0px' });

    sections.forEach(function (section) { observer.observe(section); });
})();
</script>
@endsection
