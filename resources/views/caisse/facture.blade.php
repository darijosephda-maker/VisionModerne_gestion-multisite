<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ str_pad($vente->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        @page {
            size: A4;
            margin: 8mm;
        }

        html, body {
            width: 100%;
            height: auto;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            margin: 0;
            line-height: 1.5;
            color: #333;
        }
        
        .container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
            background-color: white;
            padding: 0;
            box-shadow: 0 1px 5px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        /* === ENTÊTE === */
        .entete {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            width: 100%;
            min-height: 140px;
            padding: 15px 20px;
            background: linear-gradient(135deg, #f0f4ff 0%, #ffffff 100%);
            border-bottom: 3px solid #4f46e5;
            gap: 20px;
            flex-wrap: wrap;
        }

        .entete-entreprise {
            flex: 1;
            min-width: 250px;
            display: flex;
            align-items: flex-end;
        }
        
        .entete-logo img {
            max-width: 100%;
            max-height: 130px;
            width: auto;
            height: auto;
            display: block;
        }
        
        .entete-titre {
            flex: 0 0 auto;
            text-align: right;
            padding-bottom: 10px;
        }
        
        .entete-titre h1 {
            font-size: 38px;
            color: #4f46e5;
            font-weight: 700;
            margin: 0 0 4px 0;
            line-height: 1;
        }
        
        .entete-titre p {
            color: #666;
            font-size: 12px;
            margin: 0;
            font-weight: 500;
        }

        /* === INFO FACTURE === */
        .info-facture {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            padding: 12px 20px;
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .info-bloc {
            border-left: 3px solid #4f46e5;
            padding-left: 12px;
            font-size: 12px;
            line-height: 1.6;
        }
        
        .info-bloc h3 {
            font-size: 10px;
            text-transform: uppercase;
            color: #666;
            margin: 0 0 6px 0;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .info-bloc p {
            color: #333;
            margin: 2px 0;
            line-height: 1.4;
        }
        
        .info-bloc strong {
            color: #000;
            font-weight: 600;
        }

        /* === CLIENT INFO === */
        .client-info {
            background-color: #eff6ff;
            border-left: 3px solid #0ea5e9;
            border-bottom: 1px solid #e5e7eb;
            padding: 12px 20px;
            font-size: 12px;
            line-height: 1.6;
        }
        
        .client-info h4 {
            color: #0369a1;
            margin: 0 0 6px 0;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 700;
        }
        
        .client-info p {
            color: #0c2a47;
            margin: 2px 0;
            line-height: 1.4;
        }

        /* === TABLE === */
        .table-container {
            padding: 15px 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            font-size: 12px;
        }
        
        thead {
            background-color: #4f46e5;
            color: white;
        }
        
        th {
            padding: 8px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
            line-height: 1.4;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .quantite, .prix {
            text-align: right;
            font-weight: 500;
            width: 80px;
        }

        .quantite {
            text-align: center;
        }

        /* === TOTAL === */
        .total-section {
            display: flex;
            justify-content: flex-end;
            padding: 15px 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .total-box {
            width: 100%;
            max-width: 320px;
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: white;
            padding: 14px 16px;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
            font-size: 12px;
        }
        
        .total-ligne {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .total-final {
            display: flex;
            justify-content: space-between;
            font-size: 16px;
            font-weight: 700;
            padding-top: 8px;
            margin-top: 8px;
            border-top: 1px solid rgba(255,255,255,0.3);
        }

        /* === MENTIONS === */
        .mentions {
            background-color: #f0fdf4;
            border-left: 3px solid #22c55e;
            border-bottom: 1px solid #e5e7eb;
            padding: 10px 20px;
            font-size: 11px;
            color: #166534;
            line-height: 1.6;
            margin: 0;
        }

        /* === PIED PAGE === */
        .pied-page {
            text-align: center;
            padding: 15px 20px;
            color: #666;
            font-size: 11px;
            line-height: 1.6;
        }

        .pied-page p {
            margin: 3px 0;
        }

        /* === BOUTONS === */
        .no-print {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s ease;
        }
        
        .btn-print {
            background-color: #4f46e5;
            color: white;
            box-shadow: 0 2px 6px rgba(79, 70, 229, 0.3);
        }
        
        .btn-print:hover {
            background-color: #4338ca;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.4);
        }
        
        .btn-back {
            background-color: #e5e7eb;
            color: #374151;
        }
        
        .btn-back:hover {
            background-color: #d1d5db;
        }

        /* === IMPRESSION === */
        @media print {
            * {
                background: transparent !important;
                color: black !important;
                box-shadow: none !important;
                text-shadow: none !important;
            }
            
            html, body {
                padding: 0 !important;
                margin: 0 !important;
                height: auto !important;
                min-height: 0 !important;
                overflow: visible !important;
                background-color: white !important;
            }
            
            .container {
                box-shadow: none !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                height: auto !important;
                overflow: visible !important;
                page-break-after: avoid !important;
                break-after: avoid !important;
            }

            .entete,
            .client-info,
            .table-container,
            .total-section,
            .mentions {
                margin: 0 !important;
                padding-left: 8mm !important;
                padding-right: 8mm !important;
            }

            .entete {
                min-height: 78px !important;
                padding-top: 6px !important;
                padding-bottom: 6px !important;
            }

            .entete-logo img {
                max-height: 68px !important;
            }

            .entete-titre h1 {
                font-size: 28px !important;
            }

            .info-facture {
                gap: 8px !important;
                padding-top: 6px !important;
                padding-bottom: 6px !important;
            }

            .info-bloc {
                line-height: 1.25 !important;
            }

            .info-bloc h3 {
                margin-bottom: 3px !important;
            }

            .info-bloc p {
                margin: 1px 0 !important;
                line-height: 1.25 !important;
            }

            .client-info {
                display: none !important;
            }

            .table-container {
                padding-top: 7px !important;
                padding-bottom: 7px !important;
            }

            th {
                padding-top: 5px !important;
                padding-bottom: 5px !important;
            }

            td {
                padding-top: 4px !important;
                padding-bottom: 4px !important;
            }

            .total-section {
                padding-top: 7px !important;
                padding-bottom: 7px !important;
            }

            .total-box {
                padding-top: 8px !important;
                padding-bottom: 8px !important;
            }

            .total-ligne {
                margin-bottom: 4px !important;
            }

            .total-final {
                padding-top: 5px !important;
                margin-top: 5px !important;
            }

            .mentions {
                padding-top: 6px !important;
                padding-bottom: 6px !important;
                line-height: 1.35 !important;
            }

            .entete,
            .info-facture,
            .table-container,
            .total-section,
            .mentions,
            .pied-page {
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }

            table,
            thead,
            tbody,
            tr {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            
            .no-print {
                display: none !important;
                visibility: hidden !important;
            }

            .pied-page {
                display: block !important;
                padding-top: 5px !important;
                padding-bottom: 5px !important;
            }

            .pied-page p:not(:first-child) {
                display: none !important;
            }

            img {
                max-width: 100% !important;
                display: block !important;
            }

            body {
                padding: 0;
                margin: 0;
            }
        }

        /* === RESPONSIVE === */
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .entete {
                flex-direction: column;
                min-height: auto;
                padding: 10px 15px;
            }

            .entete-titre {
                text-align: left;
                width: 100%;
                padding-top: 10px;
            }

            .info-facture {
                grid-template-columns: 1fr;
                gap: 10px;
                padding: 10px 15px;
            }

            .total-box {
                max-width: 100%;
            }

            table, th, td {
                font-size: 11px;
            }

            th, td {
                padding: 6px 8px;
            }

            .quantite, .prix {
                width: auto;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn btn-print" onclick="window.print()">🖨️ Imprimer facture</button>
        <a href="{{ route('caisse.index', ['module' => $vente->module]) }}" class="btn btn-back">← Retour caisse</a>
    </div>

    <div class="container">
        <!-- ENTÊTE -->
        <div class="entete">
            <div class="entete-entreprise">
                <div class="entete-logo">
                    <img src="{{ asset('images/entete.png') }}" alt="Logo Entreprise">
                </div>
            </div>
            <div class="entete-titre">
                <h1>FACTURE</h1>
                <p>N° {{ str_pad($vente->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <!-- INFO FACTURE -->
        <div class="info-facture">
            <div class="info-bloc">
                <h3>Infos de vente</h3>
                <p><strong>Date :</strong> {{ $vente->date_vente->format('d/m/Y') }}</p>
                <p><strong>Heure :</strong> {{ $vente->date_vente->format('H:i:s') }}</p>
                <p><strong>Caissière :</strong> {{ $vente->caissiere->name }}</p>
                <p><strong>Module :</strong> {{ ucfirst(str_replace('_', ' ', $vente->module)) }}</p>
            </div>
            <div class="info-bloc">
                <h3>Détails factu</h3>
                <p><strong>Facture N° :</strong> {{ str_pad($vente->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Statut :</strong> <span style="color: #059669; font-weight: bold;">{{ ucfirst($vente->statut) }}</span></p>
                @if ($vente->client_nom || $vente->client_prenom || $vente->client_telephone)
                    <p><strong>Client :</strong> {{ trim($vente->client_prenom ?? '') }} {{ trim($vente->client_nom ?? '') }}</p>
                    @if ($vente->client_telephone)
                        <p><strong>Tél :</strong> {{ $vente->client_telephone }}</p>
                    @endif
                @else
                    <p style="color: #999;"><em>Vente anonyme</em></p>
                @endif
            </div>
        </div>

        @if ($vente->client_nom || $vente->client_prenom || $vente->client_telephone)
            <div class="client-info">
                <h4>👤 Client</h4>
                @if ($vente->client_prenom || $vente->client_nom)
                    <p><strong>Nom :</strong> {{ trim($vente->client_prenom ?? '') }} {{ trim($vente->client_nom ?? '') }}</p>
                @endif
                @if ($vente->client_telephone)
                    <p><strong>Téléphone :</strong> {{ $vente->client_telephone }}</p>
                @endif
            </div>
        @endif

        <!-- TABLE -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="text-align: left;">Désignation</th>
                        <th class="quantite">Qté</th>
                        <th class="prix">P. Unit</th>
                        <th class="prix">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vente->lignes as $ligne)
                        <tr>
                            <td>
                                @if ($ligne->produit_id)
                                    <strong>{{ $ligne->produit->nom ?? 'Produit supprimé' }}</strong>
                                @elseif ($ligne->type_service_id)
                                    <strong>{{ $ligne->typeService->nom ?? 'Service supprimé' }}</strong>
                                @else
                                    <strong>{{ $ligne->description_libre }}</strong>
                                @endif
                            </td>
                            <td class="quantite">{{ $ligne->quantite }}</td>
                            <td class="prix">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} F</td>
                            <td class="prix">{{ number_format($ligne->sous_total, 0, ',', ' ') }} F</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- TOTAUX -->
        <div class="total-section">
            <div class="total-box">
                <div class="total-ligne">
                    <span>Montant HT :</span>
                    <span>{{ number_format($vente->montant_total, 0, ',', ' ') }} F</span>
                </div>
                <div class="total-ligne">
                    <span>TVA (0%) :</span>
                    <span>0 F</span>
                </div>
                <div class="total-final">
                    <span>TOTAL TTC :</span>
                    <span>{{ number_format($vente->montant_total, 0, ',', ' ') }} F</span>
                </div>
            </div>
        </div>

        <!-- MENTIONS IMPORTANTES -->
        <div class="mentions">
            <strong>🔔 Important :</strong><br>
            • Facture de preuve de paiement<br>
            • Aucun droit de rétractation après achat<br>
            • Conservez cette facture pour réclamation
        </div>

        <!-- PIED PAGE -->
        <div class="pied-page">
            <p><strong>✓ Merci pour votre achat !</strong></p>
            <p>Vision Moderne Construction SARL | Tous droits réservés</p>
            <p style="font-size: 10px; margin-top: 8px;">Facture générée le {{ now()->format('d/m/Y à H:i:s') }}</p>
        </div>
    </div>

    <script>
        // Nettoyer le panier après génération de la facture
        if (window.location.pathname.includes('/facture/')) {
            try {
                localStorage.removeItem('panier_caisse');
            } catch (e) {
                console.warn('Panier non effacé:', e);
            }
        }
    </script>
</body>
</html>
