@extends('frontend.layout')

@php
    $title = __('Politique de Confidentialité');
@endphp

<x-meta-tags :meta-tag-content="$seoInfo" :page-heading="$title" />

@section('style')
<style>
    /* ── Privacy Policy ── */
    .pp-wrap {
        padding: 80px 0 100px;
        background: var(--bg-2);
    }

    /* Sidebar */
    .pp-toc {
        position: sticky;
        top: 100px;
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }
    .pp-toc__head {
        padding: 18px 22px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .pp-toc__head i { color: var(--color-primary); font-size: 14px; }
    .pp-toc__head span {
        font-family: var(--font-heading);
        font-size: 13px;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--color-dark);
    }
    .pp-toc__list { list-style: none; padding: 10px 0; margin: 0; }
    .pp-toc__list li a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 22px;
        font-size: 13.5px;
        color: var(--color-medium);
        transition: color .2s, background .2s, border-color .2s;
        border-left: 3px solid transparent;
        text-decoration: none;
    }
    .pp-toc__list li a:hover,
    .pp-toc__list li a.is-active {
        color: var(--color-primary);
        background: var(--bg-primary-light);
        border-left-color: var(--color-primary);
    }
    .pp-toc__list li a .n {
        flex-shrink: 0;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: var(--bg-1);
        font-size: 10px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--color-medium);
        transition: background .2s, color .2s;
    }
    .pp-toc__list li a:hover .n,
    .pp-toc__list li a.is-active .n {
        background: var(--color-primary);
        color: #fff;
    }
    .pp-toc__footer {
        padding: 14px 22px;
        border-top: 1px solid var(--border-color);
        background: var(--bg-2);
        font-size: 12.5px;
        color: var(--color-medium);
    }
    .pp-toc__footer a {
        font-weight: 600;
        color: var(--color-primary);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
        font-size: 13px;
    }

    /* Sections */
    .pp-section {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 36px 40px;
        margin-bottom: 20px;
        scroll-margin-top: 100px;
    }
    @media (max-width: 767.98px) {
        .pp-section { padding: 24px 18px; }
    }
    .pp-section__label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--color-primary);
        margin-bottom: 6px;
    }
    .pp-section__title {
        font-size: 20px;
        font-weight: 700;
        color: var(--color-dark);
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .pp-section__title i {
        width: 38px;
        height: 38px;
        flex-shrink: 0;
        background: var(--bg-primary-light);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--color-primary);
        font-size: 15px;
    }
    .pp-section p {
        font-size: 15px;
        line-height: 1.85;
        color: var(--color-medium);
        margin-bottom: 14px;
    }
    .pp-section p:last-child { margin-bottom: 0; }

    /* Data grid (catégories) */
    .pp-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 14px;
        margin-top: 18px;
    }
    .pp-grid__item {
        background: var(--bg-2);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 18px;
    }
    .pp-grid__item h6 {
        font-size: 13px;
        font-weight: 700;
        color: var(--color-dark);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .pp-grid__item h6 i { color: var(--color-primary); font-size: 12px; }
    .pp-grid__item ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .pp-grid__item ul li {
        font-size: 13px;
        color: var(--color-medium);
        padding: 3px 0 3px 13px;
        position: relative;
        line-height: 1.5;
    }
    .pp-grid__item ul li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 10px;
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: var(--color-primary);
        opacity: .5;
    }

    /* Check list */
    .pp-list {
        list-style: none;
        padding: 0;
        margin: 16px 0 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .pp-list li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 14.5px;
        color: var(--color-medium);
        line-height: 1.6;
    }
    .pp-list li i {
        color: var(--color-primary);
        font-size: 13px;
        margin-top: 3px;
        flex-shrink: 0;
    }

    /* Alerte / notice */
    .pp-notice {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 18px;
        border-radius: var(--radius-md);
        margin-top: 18px;
        font-size: 13.5px;
        line-height: 1.65;
    }
    .pp-notice i { font-size: 15px; margin-top: 1px; flex-shrink: 0; }
    .pp-notice p { margin: 0; font-size: 13.5px; }
    .pp-notice--warn {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-left: 3px solid var(--color-yellow);
    }
    .pp-notice--warn i,
    .pp-notice--warn p { color: #92400e; }
    .pp-notice--info {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-left: 3px solid var(--color-blue);
    }
    .pp-notice--info i,
    .pp-notice--info p { color: #1e40af; }

    /* Three column cards */
    .pp-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 14px;
        margin-top: 18px;
    }
    .pp-cards__item {
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 18px;
    }
    .pp-cards__item h6 {
        font-size: 13px;
        font-weight: 700;
        color: var(--color-dark);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .pp-cards__item h6 i { color: var(--color-primary); }
    .pp-cards__item p {
        font-size: 13px;
        color: var(--color-medium);
        margin: 0;
        line-height: 1.55;
    }

    /* Rights chips */
    .pp-rights {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 18px;
    }
    .pp-rights__chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        background: var(--bg-primary-light);
        border: 1px solid rgba(var(--color-primary-rgb), .15);
        border-radius: var(--radius-pill);
        font-size: 13px;
        font-weight: 600;
        color: var(--color-dark);
    }
    .pp-rights__chip i { color: var(--color-primary); font-size: 12px; }

    /* CTA */
    .pp-cta {
        background: var(--color-primary);
        border-radius: var(--radius-lg);
        padding: 44px 40px;
        text-align: center;
        margin-top: 28px;
    }
    .pp-cta h5 { color: #fff; font-size: 22px; font-weight: 700; margin-bottom: 10px; }
    .pp-cta p { color: rgba(255,255,255,.8); font-size: 14.5px; margin-bottom: 22px; }
    .pp-cta a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        color: var(--color-primary);
        font-weight: 700;
        font-size: 14px;
        padding: 13px 30px;
        border-radius: var(--radius-pill);
        transition: transform .2s, box-shadow .2s;
        text-decoration: none;
    }
    .pp-cta a:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.2); color: var(--color-primary); }

    /* ── Breadcrumb area ── */
    .breadcrumb-area { min-height: 220px; display: flex; align-items: center; }
</style>
@endsection

@section('content')

{{-- Breadcrumb --}}
@includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb ?? '', 'title' => $title])

{{-- Body --}}
<div class="pp-wrap">
    <div class="container">
        <div class="row gx-xl-5">

            {{-- ── SIDEBAR ── --}}
            <div class="col-xl-3 col-lg-4 d-none d-lg-block" data-aos="fade-right">
                <div class="pp-toc">
                    <div class="pp-toc__head">
                        <i class="far fa-list-ul"></i>
                        <span>{{ __('Sommaire') }}</span>
                    </div>
                    <ul class="pp-toc__list">
                        <li><a href="#s1"><span class="n">1</span>{{ __('Introduction') }}</a></li>
                        <li><a href="#s2"><span class="n">2</span>{{ __('Qui sommes-nous ?') }}</a></li>
                        <li><a href="#s3"><span class="n">3</span>{{ __('Données collectées') }}</a></li>
                        <li><a href="#s4"><span class="n">4</span>{{ __('Utilisation des données') }}</a></li>
                        <li><a href="#s5"><span class="n">5</span>{{ __('Partage des données') }}</a></li>
                        <li><a href="#s6"><span class="n">6</span>{{ __('Sécurité') }}</a></li>
                        <li><a href="#s7"><span class="n">7</span>{{ __('Conservation') }}</a></li>
                        <li><a href="#s8"><span class="n">8</span>{{ __('Vos droits') }}</a></li>
                        <li><a href="#s9"><span class="n">9</span>{{ __('Cookies') }}</a></li>
                        <li><a href="#s10"><span class="n">10</span>{{ __('Contexte africain') }}</a></li>
                        <li><a href="#s11"><span class="n">11</span>{{ __('Mineurs') }}</a></li>
                        <li><a href="#s12"><span class="n">12</span>{{ __('Modifications') }}</a></li>
                    </ul>
                    <div class="pp-toc__footer">
                        {{ __('Une question ?') }}
                        <br>
                        <a href="{{ route('contact') }}"><i class="far fa-envelope"></i>{{ __('Nous contacter') }}</a>
                    </div>
                </div>
            </div>

            {{-- ── CONTENT ── --}}
            <div class="col-xl-9 col-lg-8" data-aos="fade-up">

                {{-- 1 – Introduction --}}
                <div class="pp-section" id="s1">
                    <div class="pp-section__label">{{ __('Article') }} 01</div>
                    <div class="pp-section__title">
                        <i class="far fa-shield-alt"></i>
                        {{ __('Introduction') }}
                    </div>
                    <p>{{ __('Chez SPACEHUB, la protection de vos données personnelles constitue une priorité fondamentale. Nous nous engageons à garantir la sécurité, la confidentialité et la transparence dans le traitement de toutes vos informations.') }}</p>
                    <p>{{ __('La présente Politique de Confidentialité vous informe clairement sur les données que nous collectons, la manière dont nous les utilisons, les mesures que nous prenons pour les protéger et les droits dont vous disposez en tant qu\'utilisateur.') }}</p>
                    <div class="pp-notice pp-notice--info">
                        <i class="far fa-info-circle"></i>
                        <p>{{ __('En accédant à notre plateforme et en utilisant nos services, vous reconnaissez avoir pris connaissance de cette politique et acceptez les pratiques qui y sont décrites.') }}</p>
                    </div>
                </div>

                {{-- 2 – Qui sommes-nous --}}
                <div class="pp-section" id="s2">
                    <div class="pp-section__label">{{ __('Article') }} 02</div>
                    <div class="pp-section__title">
                        <i class="far fa-building"></i>
                        {{ __('Qui sommes-nous ?') }}
                    </div>
                    <p>{{ __('SPACEHUB est une plateforme digitale dédiée à la mise en relation entre propriétaires d\'espaces et utilisateurs à la recherche de lieux adaptés à leurs besoins.') }}</p>
                    <div class="pp-cards">
                        <div class="pp-cards__item">
                            <h6><i class="far fa-map-marker-alt"></i>{{ __('Réservation d\'espaces') }}</h6>
                            <p>{{ __('Bureaux, salles de réunion, studios, espaces événementiels et plus encore.') }}</p>
                        </div>
                        <div class="pp-cards__item">
                            <h6><i class="far fa-handshake"></i>{{ __('Mise en relation') }}</h6>
                            <p>{{ __('Connexion directe entre propriétaires d\'espaces et utilisateurs finaux.') }}</p>
                        </div>
                        <div class="pp-cards__item">
                            <h6><i class="far fa-concierge-bell"></i>{{ __('Services associés') }}</h6>
                            <p>{{ __('Gestion des services et prestations liés à chaque espace réservé.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- 3 – Données collectées --}}
                <div class="pp-section" id="s3">
                    <div class="pp-section__label">{{ __('Article') }} 03</div>
                    <div class="pp-section__title">
                        <i class="far fa-database"></i>
                        {{ __('Données collectées') }}
                    </div>
                    <p>{{ __('Nous collectons différentes catégories de données selon votre profil et vos interactions avec la plateforme.') }}</p>
                    <div class="pp-grid">
                        <div class="pp-grid__item">
                            <h6><i class="far fa-user"></i>{{ __('Données personnelles') }}</h6>
                            <ul>
                                <li>{{ __('Nom et prénom') }}</li>
                                <li>{{ __('Numéro de téléphone') }}</li>
                                <li>{{ __('Adresse e-mail') }}</li>
                                <li>{{ __('Photo de profil (optionnelle)') }}</li>
                            </ul>
                        </div>
                        <div class="pp-grid__item">
                            <h6><i class="far fa-briefcase"></i>{{ __('Données professionnelles') }}</h6>
                            <ul>
                                <li>{{ __('Nom de l\'entreprise') }}</li>
                                <li>{{ __('Adresse de l\'espace') }}</li>
                                <li>{{ __('Informations de facturation') }}</li>
                                <li>{{ __('N° d\'identification') }}</li>
                            </ul>
                        </div>
                        <div class="pp-grid__item">
                            <h6><i class="far fa-calendar-check"></i>{{ __('Données de réservation') }}</h6>
                            <ul>
                                <li>{{ __('Détails des réservations') }}</li>
                                <li>{{ __('Historique des transactions') }}</li>
                                <li>{{ __('Préférences utilisateur') }}</li>
                            </ul>
                        </div>
                        <div class="pp-grid__item">
                            <h6><i class="far fa-laptop"></i>{{ __('Données techniques') }}</h6>
                            <ul>
                                <li>{{ __('Adresse IP') }}</li>
                                <li>{{ __('Type d\'appareil') }}</li>
                                <li>{{ __('Navigateur utilisé') }}</li>
                                <li>{{ __('Données de navigation') }}</li>
                            </ul>
                        </div>
                        <div class="pp-grid__item">
                            <h6><i class="far fa-credit-card"></i>{{ __('Données de paiement') }}</h6>
                            <ul>
                                <li>{{ __('Données de transaction') }}</li>
                                <li>{{ __('Mobile Money') }}</li>
                                <li>{{ __('Cartes (données partielles)') }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="pp-notice pp-notice--warn">
                        <i class="far fa-exclamation-triangle"></i>
                        <p>{{ __('Nous ne stockons jamais vos données bancaires complètes. Les informations sensibles de paiement sont traitées par des prestataires certifiés et sécurisés.') }}</p>
                    </div>
                </div>

                {{-- 4 – Utilisation --}}
                <div class="pp-section" id="s4">
                    <div class="pp-section__label">{{ __('Article') }} 04</div>
                    <div class="pp-section__title">
                        <i class="far fa-cogs"></i>
                        {{ __('Utilisation des données') }}
                    </div>
                    <p>{{ __('Vos données sont utilisées exclusivement dans le cadre des finalités suivantes :') }}</p>
                    <ul class="pp-list">
                        <li><i class="far fa-check-circle"></i>{{ __('Fournir nos services et assurer leur amélioration continue') }}</li>
                        <li><i class="far fa-check-circle"></i>{{ __('Gérer, confirmer et suivre vos réservations') }}</li>
                        <li><i class="far fa-check-circle"></i>{{ __('Faciliter et sécuriser les transactions de paiement') }}</li>
                        <li><i class="far fa-check-circle"></i>{{ __('Assurer la sécurité et l\'intégrité de la plateforme') }}</li>
                        <li><i class="far fa-check-circle"></i>{{ __('Communiquer avec vous par SMS, WhatsApp ou e-mail') }}</li>
                        <li><i class="far fa-check-circle"></i>{{ __('Personnaliser votre expérience sur la plateforme') }}</li>
                        <li><i class="far fa-check-circle"></i>{{ __('Respecter nos obligations légales et réglementaires') }}</li>
                    </ul>
                </div>

                {{-- 5 – Partage --}}
                <div class="pp-section" id="s5">
                    <div class="pp-section__label">{{ __('Article') }} 05</div>
                    <div class="pp-section__title">
                        <i class="far fa-share-alt"></i>
                        {{ __('Partage des données') }}
                    </div>
                    <p>{{ __('SPACEHUB ne vend, ne loue ni ne cède vos données personnelles à des tiers à des fins commerciales. Certaines informations peuvent néanmoins être partagées avec des partenaires de confiance, strictement nécessaires au bon fonctionnement de la plateforme.') }}</p>
                    <div class="pp-cards">
                        <div class="pp-cards__item">
                            <h6><i class="far fa-server"></i>{{ __('Partenaires techniques') }}</h6>
                            <p>{{ __('Services de paiement (Mobile Money, APIs), hébergeurs et infrastructure cloud.') }}</p>
                        </div>
                        <div class="pp-cards__item">
                            <h6><i class="far fa-headset"></i>{{ __('Prestataires de services') }}</h6>
                            <p>{{ __('Support client, outils d\'envoi de SMS et d\'e-mails transactionnels.') }}</p>
                        </div>
                        <div class="pp-cards__item">
                            <h6><i class="far fa-balance-scale"></i>{{ __('Autorités compétentes') }}</h6>
                            <p>{{ __('Uniquement en cas d\'obligation légale, judiciaire ou réglementaire.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- 6 – Sécurité --}}
                <div class="pp-section" id="s6">
                    <div class="pp-section__label">{{ __('Article') }} 06</div>
                    <div class="pp-section__title">
                        <i class="far fa-lock"></i>
                        {{ __('Sécurité des données') }}
                    </div>
                    <p>{{ __('Nous mettons en œuvre des mesures techniques et organisationnelles rigoureuses pour protéger vos données contre tout accès non autorisé, perte, altération ou divulgation.') }}</p>
                    <ul class="pp-list">
                        <li><i class="far fa-lock"></i>{{ __('Chiffrement des données sensibles') }}</li>
                        <li><i class="far fa-key"></i>{{ __('Accès sécurisé aux comptes utilisateurs') }}</li>
                        <li><i class="far fa-eye"></i>{{ __('Surveillance continue des activités suspectes') }}</li>
                        <li><i class="far fa-server"></i>{{ __('Hébergement sur serveurs certifiés et sécurisés') }}</li>
                    </ul>
                    <div class="pp-notice pp-notice--warn">
                        <i class="far fa-exclamation-triangle"></i>
                        <p>{{ __('Malgré la rigueur de nos pratiques, aucun système d\'information n\'est infaillible. Nous vous recommandons de protéger votre compte avec un mot de passe robuste et de ne jamais le partager.') }}</p>
                    </div>
                </div>

                {{-- 7 – Conservation --}}
                <div class="pp-section" id="s7">
                    <div class="pp-section__label">{{ __('Article') }} 07</div>
                    <div class="pp-section__title">
                        <i class="far fa-clock"></i>
                        {{ __('Conservation des données') }}
                    </div>
                    <p>{{ __('Vos données personnelles sont conservées uniquement pour la durée nécessaire à l\'accomplissement des finalités pour lesquelles elles ont été collectées.') }}</p>
                    <ul class="pp-list">
                        <li><i class="far fa-check-circle"></i>{{ __('Tant que votre compte est actif et nécessaire à la fourniture de nos services') }}</li>
                        <li><i class="far fa-check-circle"></i>{{ __('Pour satisfaire nos obligations légales, fiscales et comptables') }}</li>
                        <li><i class="far fa-check-circle"></i>{{ __('Jusqu\'à la suppression de votre compte, sur demande expresse') }}</li>
                    </ul>
                </div>

                {{-- 8 – Vos droits --}}
                <div class="pp-section" id="s8">
                    <div class="pp-section__label">{{ __('Article') }} 08</div>
                    <div class="pp-section__title">
                        <i class="far fa-user-shield"></i>
                        {{ __('Vos droits') }}
                    </div>
                    <p>{{ __('Conformément aux bonnes pratiques internationales en matière de protection des données, vous disposez des droits suivants :') }}</p>
                    <div class="pp-rights">
                        <div class="pp-rights__chip"><i class="far fa-eye"></i>{{ __('Droit d\'accès') }}</div>
                        <div class="pp-rights__chip"><i class="far fa-edit"></i>{{ __('Droit de rectification') }}</div>
                        <div class="pp-rights__chip"><i class="far fa-trash-alt"></i>{{ __('Droit à l\'effacement') }}</div>
                        <div class="pp-rights__chip"><i class="far fa-ban"></i>{{ __('Droit d\'opposition') }}</div>
                        <div class="pp-rights__chip"><i class="far fa-undo"></i>{{ __('Retrait du consentement') }}</div>
                        <div class="pp-rights__chip"><i class="far fa-download"></i>{{ __('Portabilité des données') }}</div>
                    </div>
                    <div class="pp-notice pp-notice--info">
                        <i class="far fa-info-circle"></i>
                        <p>{{ __('Pour exercer l\'un de vos droits, contactez-nous via le formulaire disponible sur la plateforme. Nous nous engageons à répondre dans les meilleurs délais.') }}</p>
                    </div>
                </div>

                {{-- 9 – Cookies --}}
                <div class="pp-section" id="s9">
                    <div class="pp-section__label">{{ __('Article') }} 09</div>
                    <div class="pp-section__title">
                        <i class="far fa-cookie-bite"></i>
                        {{ __('Cookies et technologies similaires') }}
                    </div>
                    <p>{{ __('SPACEHUB utilise des cookies afin d\'améliorer votre expérience de navigation et d\'optimiser nos services.') }}</p>
                    <ul class="pp-list">
                        <li><i class="far fa-check-circle"></i>{{ __('Amélioration de la navigation et de la fluidité de l\'interface') }}</li>
                        <li><i class="far fa-check-circle"></i>{{ __('Analyse statistique de l\'utilisation de la plateforme') }}</li>
                        <li><i class="far fa-check-circle"></i>{{ __('Personnalisation du contenu selon vos préférences') }}</li>
                    </ul>
                    <p class="mt-15">{{ __('Vous pouvez paramétrer votre navigateur pour refuser tout ou partie des cookies. Cela peut toutefois affecter certaines fonctionnalités.') }}</p>
                </div>

                {{-- 10 – Contexte africain --}}
                <div class="pp-section" id="s10">
                    <div class="pp-section__label">{{ __('Article') }} 10</div>
                    <div class="pp-section__title">
                        <i class="far fa-globe-africa"></i>
                        {{ __('Spécificités du marché africain') }}
                    </div>
                    <p>{{ __('SPACEHUB est conçu pour répondre aux réalités économiques et technologiques du marché africain, notamment en République Démocratique du Congo.') }}</p>
                    <div class="pp-cards">
                        <div class="pp-cards__item">
                            <h6><i class="far fa-mobile-alt"></i>{{ __('Paiements locaux') }}</h6>
                            <p>{{ __('Intégration de solutions Mobile Money adaptées aux habitudes de paiement locales.') }}</p>
                        </div>
                        <div class="pp-cards__item">
                            <h6><i class="far fa-store"></i>{{ __('Économie informelle') }}</h6>
                            <p>{{ __('Collecte de données adaptée à la réalité du marché, sans exigences administratives excessives.') }}</p>
                        </div>
                        <div class="pp-cards__item">
                            <h6><i class="far fa-users"></i>{{ __('Inclusion numérique') }}</h6>
                            <p>{{ __('Prérequis limités pour favoriser l\'accès au plus grand nombre d\'utilisateurs.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- 11 – Mineurs --}}
                <div class="pp-section" id="s11">
                    <div class="pp-section__label">{{ __('Article') }} 11</div>
                    <div class="pp-section__title">
                        <i class="far fa-child"></i>
                        {{ __('Protection des mineurs') }}
                    </div>
                    <p>{{ __('Les services de SPACEHUB sont exclusivement destinés aux personnes âgées de 18 ans et plus. Nous ne collectons pas sciemment de données concernant des mineurs.') }}</p>
                    <p>{{ __('Si vous êtes parent ou tuteur légal et constatez qu\'un mineur nous a communiqué ses données, contactez-nous afin que nous procédions à leur suppression dans les meilleurs délais.') }}</p>
                </div>

                {{-- 12 – Modifications --}}
                <div class="pp-section" id="s12">
                    <div class="pp-section__label">{{ __('Article') }} 12</div>
                    <div class="pp-section__title">
                        <i class="far fa-sync-alt"></i>
                        {{ __('Modifications de la politique') }}
                    </div>
                    <p>{{ __('SPACEHUB se réserve le droit de mettre à jour la présente Politique de Confidentialité à tout moment, notamment pour s\'adapter aux évolutions légales ou opérationnelles.') }}</p>
                    <p>{{ __('Toute modification substantielle vous sera notifiée via nos canaux de communication habituels. La date de dernière mise à jour figurant en tête de ce document fait foi.') }}</p>
                </div>

                {{-- CTA --}}
                <div class="pp-cta">
                    <h5>{{ __('Une question sur vos données personnelles ?') }}</h5>
                    <p>{{ __('Notre équipe est disponible pour répondre à toutes vos questions sur la confidentialité et la gestion de vos informations.') }}</p>
                    <a href="{{ route('contact') }}">
                        <i class="far fa-envelope"></i>
                        {{ __('Contacter notre équipe') }}
                    </a>
                </div>

            </div>{{-- /col --}}
        </div>{{-- /row --}}
    </div>{{-- /container --}}
</div>

@endsection

@section('script')
<script>
(function () {
    var sections = document.querySelectorAll('.pp-section[id]');
    var links    = document.querySelectorAll('.pp-toc__list a');
    if (!sections.length || !links.length) return;

    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
            if (e.isIntersecting) {
                links.forEach(function (l) { l.classList.remove('is-active'); });
                var a = document.querySelector('.pp-toc__list a[href="#' + e.target.id + '"]');
                if (a) a.classList.add('is-active');
            }
        });
    }, { rootMargin: '-20% 0px -70% 0px' });

    sections.forEach(function (s) { io.observe(s); });
})();
</script>
@endsection
