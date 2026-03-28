@extends('frontend.layout')

@php
    $title = __('Conditions Generales d\'Utilisation');
@endphp

<x-meta-tags :meta-tag-content="$seoInfo" :page-heading="$title" />

@section('style')
<style>
    .terms-wrap {
        padding: 80px 0 100px;
        background: var(--bg-2);
    }

    .terms-container {
        max-width: 1040px;
        margin: 0 auto;
    }

    .terms-header,
    .terms-toc,
    .terms-section,
    .terms-cta {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
    }

    .terms-header {
        padding: 34px;
        margin-bottom: 16px;
    }

    .terms-header h2 {
        font-size: 32px;
        margin-bottom: 10px;
        line-height: 1.25;
    }

    .terms-header p {
        font-size: 15px;
        line-height: 1.85;
        color: var(--color-medium);
        margin-bottom: 8px;
    }

    .terms-meta {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-top: 8px;
        padding: 8px 12px;
        border-radius: var(--radius-sm);
        background: var(--bg-primary-light);
        border: 1px solid rgba(var(--color-primary-rgb), 0.15);
        color: var(--color-primary);
        font-size: 12px;
        font-weight: 600;
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    .terms-toc {
        padding: 20px 24px;
        margin-bottom: 16px;
    }

    .terms-toc h4 {
        font-size: 13px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .terms-toc ul {
        list-style: none;
        margin: 0;
        padding: 0;
        columns: 2;
        column-gap: 26px;
    }

    .terms-toc li {
        break-inside: avoid;
        margin-bottom: 8px;
    }

    .terms-toc a {
        display: inline-flex;
        gap: 8px;
        color: var(--color-medium);
        font-size: 13.5px;
        line-height: 1.5;
        text-decoration: none;
    }

    .terms-toc a:hover,
    .terms-toc a.active {
        color: var(--color-primary);
        font-weight: 600;
    }

    .terms-section {
        padding: 30px 34px;
        margin-bottom: 14px;
        scroll-margin-top: 110px;
    }

    .terms-section .sec-number {
        font-size: 11px;
        letter-spacing: .1em;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--color-primary);
        margin-bottom: 6px;
    }

    .terms-section h3 {
        font-size: 22px;
        margin-bottom: 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border-color);
    }

    .terms-section p {
        font-size: 15px;
        line-height: 1.85;
        color: var(--color-medium);
        margin-bottom: 10px;
    }

    .terms-list {
        margin: 10px 0 0;
        padding-left: 18px;
    }

    .terms-list li {
        font-size: 15px;
        line-height: 1.8;
        color: var(--color-medium);
        margin-bottom: 4px;
    }

    .terms-columns {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 12px;
        margin-top: 10px;
    }

    .terms-box {
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        background: var(--bg-2);
        padding: 14px;
    }

    .terms-box h5 {
        font-size: 14px;
        margin-bottom: 6px;
    }

    .terms-box ul {
        margin: 0;
        padding-left: 16px;
    }

    .terms-box li {
        font-size: 13.5px;
        line-height: 1.7;
        color: var(--color-medium);
    }

    .terms-note {
        margin-top: 12px;
        padding: 12px 14px;
        border: 1px solid #fde68a;
        background: #fffbeb;
        border-radius: var(--radius-sm);
        color: #92400e;
        font-size: 13.5px;
        line-height: 1.65;
    }

    .terms-cta {
        text-align: center;
        padding: 28px 24px;
    }

    .terms-cta h4 {
        font-size: 22px;
        margin-bottom: 8px;
    }

    .terms-cta p {
        max-width: 700px;
        margin: 0 auto 16px;
        color: var(--color-medium);
        line-height: 1.75;
    }

    .breadcrumb-area {
        min-height: 220px;
        display: flex;
        align-items: center;
    }

    @media (max-width: 767.98px) {
        .terms-header,
        .terms-toc,
        .terms-section,
        .terms-cta {
            padding: 20px 18px;
        }

        .terms-header h2 {
            font-size: 25px;
        }

        .terms-toc ul {
            columns: 1;
        }
    }
</style>
@endsection

@section('content')
@includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $breadcrumb ?? '', 'title' => $title])

<section class="terms-wrap">
    <div class="container">
        <div class="terms-container" data-aos="fade-up">
            <div class="terms-header">
                <h2>{{ __('Conditions Generales d\'Utilisation') }}</h2>
                <p>{{ __('Les presentes Conditions Generales d\'Utilisation (CGU) definissent les regles d\'acces et d\'utilisation de la plateforme SPACEHUB.') }}</p>
                <p>{{ __('En accedant a la plateforme, chaque utilisateur accepte pleinement les dispositions decrites dans le present document.') }}</p>
                <span class="terms-meta">{{ __('Version en vigueur') }} - 2026</span>
            </div>

            <nav class="terms-toc">
                <h4>{{ __('Sommaire') }}</h4>
                <ul>
                    <li><a href="#s1"><span>1.</span><span>{{ __('Objet') }}</span></a></li>
                    <li><a href="#s2"><span>2.</span><span>{{ __('Definitions') }}</span></a></li>
                    <li><a href="#s3"><span>3.</span><span>{{ __('Acces a la plateforme') }}</span></a></li>
                    <li><a href="#s4"><span>4.</span><span>{{ __('Creation de compte') }}</span></a></li>
                    <li><a href="#s5"><span>5.</span><span>{{ __('Services proposes') }}</span></a></li>
                    <li><a href="#s6"><span>6.</span><span>{{ __('Reservations') }}</span></a></li>
                    <li><a href="#s7"><span>7.</span><span>{{ __('Paiements') }}</span></a></li>
                    <li><a href="#s8"><span>8.</span><span>{{ __('Annulation et remboursement') }}</span></a></li>
                    <li><a href="#s9"><span>9.</span><span>{{ __('Obligations des utilisateurs') }}</span></a></li>
                    <li><a href="#s10"><span>10.</span><span>{{ __('Contenus publies') }}</span></a></li>
                    <li><a href="#s11"><span>11.</span><span>{{ __('Responsabilite') }}</span></a></li>
                    <li><a href="#s12"><span>12.</span><span>{{ __('Securite et fraude') }}</span></a></li>
                    <li><a href="#s13"><span>13.</span><span>{{ __('Donnees personnelles') }}</span></a></li>
                    <li><a href="#s14"><span>14.</span><span>{{ __('Propriete intellectuelle') }}</span></a></li>
                    <li><a href="#s15"><span>15.</span><span>{{ __('Suspension et resiliation') }}</span></a></li>
                    <li><a href="#s16"><span>16.</span><span>{{ __('Specificites africaines') }}</span></a></li>
                    <li><a href="#s17"><span>17.</span><span>{{ __('Modification des CGU') }}</span></a></li>
                    <li><a href="#s18"><span>18.</span><span>{{ __('Droit applicable') }}</span></a></li>
                </ul>
            </nav>

            <article class="terms-section" id="s1">
                <div class="sec-number">Article 01</div>
                <h3>{{ __('Objet') }}</h3>
                <p>{{ __('Les presentes CGU ont pour objet de definir les regles d\'acces et d\'utilisation de la plateforme SPACEHUB.') }}</p>
                <ul class="terms-list">
                    <li>{{ __('Reservation d\'espaces (bureaux, salles, studios, evenements et autres)') }}</li>
                    <li>{{ __('Mise en relation entre proprietaires d\'espaces et utilisateurs') }}</li>
                    <li>{{ __('Gestion de services associes aux espaces') }}</li>
                </ul>
            </article>

            <article class="terms-section" id="s2">
                <div class="sec-number">Article 02</div>
                <h3>{{ __('Definitions') }}</h3>
                <ul class="terms-list">
                    <li>{{ __('Plateforme : le site web et les applications SPACEHUB') }}</li>
                    <li>{{ __('Utilisateur : toute personne utilisant la plateforme') }}</li>
                    <li>{{ __('Vendeur ou Proprietaire : utilisateur proposant un espace a la location') }}</li>
                    <li>{{ __('Client ou Reservant : utilisateur reservant un espace') }}</li>
                    <li>{{ __('Reservation : operation de location effectuee via la plateforme') }}</li>
                </ul>
            </article>

            <article class="terms-section" id="s3">
                <div class="sec-number">Article 03</div>
                <h3>{{ __('Acces a la plateforme') }}</h3>
                <p>{{ __('L\'acces a SPACEHUB est ouvert a toute personne disposant d\'un acces internet et d\'un appareil compatible.') }}</p>
                <p>{{ __('Certaines fonctionnalites necessitent la creation prealable d\'un compte utilisateur.') }}</p>
            </article>

            <article class="terms-section" id="s4">
                <div class="sec-number">Article 04</div>
                <h3>{{ __('Creation de compte') }}</h3>
                <p>{{ __('L\'utilisateur s\'engage a fournir des informations exactes, completes et mises a jour.') }}</p>
                <ul class="terms-list">
                    <li>{{ __('Ne pas usurper l\'identite d\'autrui') }}</li>
                    <li>{{ __('Securiser ses identifiants de connexion') }}</li>
                    <li>{{ __('Informer SPACEHUB de toute utilisation suspecte de son compte') }}</li>
                </ul>
                <p>{{ __('SPACEHUB se reserve le droit de suspendre tout compte frauduleux ou non conforme.') }}</p>
            </article>

            <article class="terms-section" id="s5">
                <div class="sec-number">Article 05</div>
                <h3>{{ __('Services proposes') }}</h3>
                <div class="terms-columns">
                    <div class="terms-box">
                        <h5>{{ __('Pour les clients') }}</h5>
                        <ul>
                            <li>{{ __('Rechercher des espaces') }}</li>
                            <li>{{ __('Effectuer des reservations') }}</li>
                            <li>{{ __('Payer en ligne ou via des solutions locales') }}</li>
                        </ul>
                    </div>
                    <div class="terms-box">
                        <h5>{{ __('Pour les vendeurs') }}</h5>
                        <ul>
                            <li>{{ __('Publier des espaces') }}</li>
                            <li>{{ __('Gerer les disponibilites') }}</li>
                            <li>{{ __('Recevoir des reservations et generer des revenus') }}</li>
                        </ul>
                    </div>
                </div>
            </article>

            <article class="terms-section" id="s6">
                <div class="sec-number">Article 06</div>
                <h3>{{ __('Reservations') }}</h3>
                <ul class="terms-list">
                    <li>{{ __('Toute reservation est soumise a la disponibilite de l\'espace') }}</li>
                    <li>{{ __('Le client respecte les conditions de l\'espace reserve') }}</li>
                    <li>{{ __('Le vendeur fournit un service conforme a la description publiee') }}</li>
                </ul>
                <div class="terms-note">{{ __('SPACEHUB agit comme intermediaire et ne garantit pas l\'execution parfaite du service entre utilisateurs.') }}</div>
            </article>

            <article class="terms-section" id="s7">
                <div class="sec-number">Article 07</div>
                <h3>{{ __('Paiements') }}</h3>
                <p>{{ __('Les paiements peuvent etre realises via Mobile Money, cartes bancaires et autres solutions locales disponibles.') }}</p>
                <ul class="terms-list">
                    <li>{{ __('Transactions securisees') }}</li>
                    <li>{{ __('Prelevement possible d\'une commission par SPACEHUB') }}</li>
                    <li>{{ __('Versement des parts vendeurs selon les conditions applicables') }}</li>
                </ul>
            </article>

            <article class="terms-section" id="s8">
                <div class="sec-number">Article 08</div>
                <h3>{{ __('Annulation et remboursement') }}</h3>
                <p>{{ __('Chaque vendeur peut definir sa politique d\'annulation selon ses conditions commerciales.') }}</p>
                <ul class="terms-list">
                    <li>{{ __('Annulation par le client : remboursement selon les conditions du vendeur') }}</li>
                    <li>{{ __('Annulation par le vendeur : remboursement integral du client') }}</li>
                </ul>
                <p>{{ __('SPACEHUB peut intervenir en cas de litige entre les parties.') }}</p>
            </article>

            <article class="terms-section" id="s9">
                <div class="sec-number">Article 09</div>
                <h3>{{ __('Obligations des utilisateurs') }}</h3>
                <div class="terms-columns">
                    <div class="terms-box">
                        <h5>{{ __('Clients') }}</h5>
                        <ul>
                            <li>{{ __('Respecter les lieux, equipements et horaires') }}</li>
                            <li>{{ __('Ne pas causer de dommages') }}</li>
                        </ul>
                    </div>
                    <div class="terms-box">
                        <h5>{{ __('Vendeurs') }}</h5>
                        <ul>
                            <li>{{ __('Publier des informations exactes') }}</li>
                            <li>{{ __('Garantir la disponibilite reelle') }}</li>
                            <li>{{ __('Offrir un service conforme') }}</li>
                        </ul>
                    </div>
                </div>
            </article>

            <article class="terms-section" id="s10">
                <div class="sec-number">Article 10</div>
                <h3>{{ __('Contenus publies') }}</h3>
                <p>{{ __('Les vendeurs sont responsables des descriptions, images et informations publiees sur la plateforme.') }}</p>
                <p>{{ __('SPACEHUB se reserve le droit de retirer tout contenu frauduleux, trompeur ou inapproprie.') }}</p>
            </article>

            <article class="terms-section" id="s11">
                <div class="sec-number">Article 11</div>
                <h3>{{ __('Responsabilite') }}</h3>
                <p>{{ __('SPACEHUB agit comme intermediaire technique entre utilisateurs.') }}</p>
                <ul class="terms-list">
                    <li>{{ __('SPACEHUB ne garantit pas la qualite des services fournis par les vendeurs') }}</li>
                    <li>{{ __('SPACEHUB ne peut etre tenu responsable des dommages survenus lors d\'une reservation') }}</li>
                    <li>{{ __('SPACEHUB ne peut etre tenu responsable des litiges entre utilisateurs') }}</li>
                    <li>{{ __('SPACEHUB ne couvre pas les pertes financieres indirectes') }}</li>
                </ul>
            </article>

            <article class="terms-section" id="s12">
                <div class="sec-number">Article 12</div>
                <h3>{{ __('Securite et fraude') }}</h3>
                <p>{{ __('SPACEHUB met en place des mesures de prevention de la fraude et de securisation des transactions.') }}</p>
                <p>{{ __('Tout comportement suspect peut entrainer la suspension du compte et le blocage des transactions.') }}</p>
            </article>

            <article class="terms-section" id="s13">
                <div class="sec-number">Article 13</div>
                <h3>{{ __('Donnees personnelles') }}</h3>
                <p>{{ __('Les donnees personnelles sont traitees conformement a la Politique de Confidentialite de SPACEHUB.') }}</p>
            </article>

            <article class="terms-section" id="s14">
                <div class="sec-number">Article 14</div>
                <h3>{{ __('Propriete intellectuelle') }}</h3>
                <p>{{ __('Tous les elements de la plateforme (logo, design, contenus, marques) sont proteges par les regles applicables en matiere de propriete intellectuelle.') }}</p>
                <p>{{ __('Toute reproduction, diffusion ou utilisation sans autorisation prealable est interdite.') }}</p>
            </article>

            <article class="terms-section" id="s15">
                <div class="sec-number">Article 15</div>
                <h3>{{ __('Suspension et resiliation') }}</h3>
                <p>{{ __('SPACEHUB peut suspendre ou supprimer un compte en cas de non-respect des CGU, de fraude ou d\'utilisation abusive des services.') }}</p>
            </article>

            <article class="terms-section" id="s16">
                <div class="sec-number">Article 16</div>
                <h3>{{ __('Specificites africaines') }}</h3>
                <p>{{ __('Dans le contexte africain, et particulierement en RDC :') }}</p>
                <ul class="terms-list">
                    <li>{{ __('Les paiements peuvent inclure des solutions Mobile Money') }}</li>
                    <li>{{ __('Certains services peuvent etre fournis dans un cadre semi-formel') }}</li>
                    <li>{{ __('SPACEHUB favorise l\'inclusion des petits operateurs') }}</li>
                </ul>
            </article>

            <article class="terms-section" id="s17">
                <div class="sec-number">Article 17</div>
                <h3>{{ __('Modification des CGU') }}</h3>
                <p>{{ __('Les presentes CGU peuvent etre modifiees a tout moment.') }}</p>
                <p>{{ __('Les utilisateurs sont informes en cas de changement majeur.') }}</p>
            </article>

            <article class="terms-section" id="s18">
                <div class="sec-number">Article 18</div>
                <h3>{{ __('Droit applicable') }}</h3>
                <p>{{ __('Les presentes CGU sont regies par les lois en vigueur en Republique Democratique du Congo.') }}</p>
            </article>

            <section class="terms-cta">
                <h4>{{ __('Besoin d\'information complementaire ?') }}</h4>
                <p>{{ __('Pour toute question relative aux conditions d\'utilisation de la plateforme, contactez notre equipe support.') }}</p>
                <a href="{{ route('contact') }}" class="btn btn-lg btn-primary">{{ __('Contacter le support') }}</a>
            </section>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
(function () {
    var sections = document.querySelectorAll('.terms-section[id]');
    var links = document.querySelectorAll('.terms-toc a');
    if (!sections.length || !links.length) return;

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                links.forEach(function (link) { link.classList.remove('active'); });
                var active = document.querySelector('.terms-toc a[href="#' + entry.target.id + '"]');
                if (active) active.classList.add('active');
            }
        });
    }, { rootMargin: '-25% 0px -65% 0px' });

    sections.forEach(function (section) { observer.observe(section); });
})();
</script>
@endsection
