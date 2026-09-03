<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $vente->id }}</title>
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

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            padding: 25px 25px 20px;
            margin: 0;
        }
        
        .container {
            width: 100%;
            max-width: 980px;
            margin: 0 auto;
            background-color: white;
            padding: 0 0 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        .entete {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            width: 100%;
            min-height: 170px;
            margin: 0 0 20px;
            padding: 12px 22px 10px;
            background: linear-gradient(90deg, #eef2ff 0%, #ffffff 100%);
            border-bottom: 3px solid #4f46e5;
            box-sizing: border-box;
        }

        .entete-entreprise {
            display: flex;
            align-items: flex-end;
            flex: 1;
            min-width: 0;
            height: 100%;
        }
        
        .entete-logo {
            width: 100%;
            max-width: 620px;
            min-width: 260px;
            display: flex;
            align-items: flex-end;
            height: 100%;
        }
        
        .entete-logo img {
            width: 100%;
            height: auto;
            max-height: 160px;
            object-fit: contain;
            display: block;
        }
        
        .entete-titre {
            text-align: right;
            min-width: 210px;
        }
        
        .entete-titre h1 {
            font-size: 42px;
            color: #4f46e5;
            font-weight: bold;
            margin-bottom: 6px;
            line-height: 1;
            letter-spacing: 1px;
        }
        
        .entete-titre p {
            color: #666;
            font-size: 14px;
        }
        
        .info-facture {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 0 18px 18px;
            padding: 14px 18px;
            background-color: #f9fafb;
            border-radius: 8px;
        }
        
        .info-bloc {
            border-left: 4px solid #4f46e5;
            padding-left: 15px;
        }
        
        .info-bloc h3 {
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .info-bloc p {
            font-size: 14px;
            color: #333;
            margin-bottom: 4px;
        }
        
        .info-bloc p strong {
            color: #000;
        }
        
        .table-container {
            margin: 0 18px 18px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        
        thead {
            background-color: #4f46e5;
            color: white;
        }
        
        th {
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        td {
            padding: 9px 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }
        
        tbody tr:hover {
            background-color: #f9fafb;
        }
        
        tbody tr:last-child td {
            border-bottom: 2px solid #4f46e5;
        }
        
        .quantite {
            text-align: center;
            font-weight: 600;
        }
        
        .prix {
            text-align: right;
            font-weight: 600;
        }
        
        .total-section {
            display: flex;
            justify-content: flex-end;
            margin: 0 18px 18px;
        }
        
        .total-box {
            width: 350px;
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: white;
            padding: 18px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }
        
        .total-ligne {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .total-ligne-label {
            font-weight: 600;
        }
        
        .total-final {
            display: flex;
            justify-content: space-between;
            font-size: 24px;
            font-weight: bold;
            padding-top: 15px;
            border-top: 2px solid rgba(255,255,255,0.3);
        }
        
        .mentions {
            background-color: #f0fdf4;
            border-left: 4px solid #22c55e;
            padding: 12px 16px;
            border-radius: 4px;
            font-size: 12px;
            color: #166534;
            margin: 0 18px 12px;
            line-height: 1.5;
        }
        
        .pied-page {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #666;
            font-size: 12px;
            margin: 0 18px;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        
        .client-info {
            background-color: #eff6ff;
            border-left: 4px solid #0ea5e9;
            padding: 15px 18px;
            border-radius: 4px;
            margin: 0 18px 20px;
        }
        
        .client-info h4 {
            color: #0369a1;
            margin-bottom: 8px;
            font-size: 13px;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .client-info p {
            color: #0c2a47;
            font-size: 14px;
            margin: 3px 0;
        }
        
        .no-print {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            justify-content: flex-end;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-print {
            background-color: #4f46e5;
            color: white;
        }
        
        .btn-print:hover {
            background-color: #4338ca;
        }
        
        .btn-back {
            background-color: #e5e7eb;
            color: #374151;
        }
        
        .btn-back:hover {
            background-color: #d1d5db;
        }
        
        @media print {
            * {
                background: transparent !important;
                color: black !important;
                box-shadow: none !important;
                text-shadow: none !important;
            }
            
            body {
                background-color: white !important;
                padding: 0 !important;
                margin: 0 !important;
                font-family: Arial, sans-serif;
            }
            
            .container {
                box-shadow: none !important;
                padding: 0 !important;
                max-width: 100% !important;
                page-break-inside: avoid !important;
                margin: 0 !important;
            }

            .entete {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                margin-bottom: 15px !important;
            }

            .info-facture {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            
            .client-info {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            
            .table-container {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            
            .total-section {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            
            .mentions {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            
            .pied-page {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            
            .no-print {
                display: none !important;
            }

            /* Assurer la visibilité du contenu */
            table, thead, tbody, tr, td, th {
                page-break-inside: avoid !important;
            }

            tr {
                page-break-inside: avoid !important;
            }

            img {
                max-width: 100% !important;
                display: block !important;
            }
        }
        
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
            
            .entete {
                flex-direction: column;
                text-align: center;
            }
            
            .entete-titre {
                text-align: center;
                margin-top: 20px;
            }
            
            .info-facture {
                grid-template-columns: 1fr;
            }
            
            table {
                font-size: 12px;
            }
            
            th, td {
                padding: 8px;
            }
            
            .total-box {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="no-print">
            <button class="btn btn-print" onclick="window.print()">🖨️ Imprimer la facture</button>
            <a href="{{ route('caisse.index', ['module' => $vente->module]) }}" class="btn btn-back">← Retour à la caisse</a>
        </div>

        <div class="entete">
            <div class="entete-entreprise">
                <div class="entete-logo">
                    <img src="{{ asset('images/entete.png') }}" alt="Logo">
                </div>
            </div>
            <div class="entete-titre">
                <h1>FACTURE</h1>
                <p>N° {{ str_pad($vente->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <div class="info-facture">
            <div class="info-bloc">
                <h3>Informations de vente</h3>
                <p><strong>Date :</strong> {{ $vente->date_vente->format('d/m/Y') }}</p>
                <p><strong>Heure :</strong> {{ $vente->date_vente->format('H:i:s') }}</p>
                <p><strong>Caissière :</strong> {{ $vente->caissiere->name }}</p>
                <p><strong>Module :</strong> {{ ucfirst(str_replace('_', ' ', $vente->module)) }}</p>
            </div>
            <div class="info-bloc">
                <h3>Détails de facturation</h3>
                <p><strong>Facture N° :</strong> {{ str_pad($vente->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Statut :</strong> <span style="color: #059669; font-weight: bold;">{{ ucfirst($vente->statut) }}</span></p>
                @if ($vente->client_nom || $vente->client_prenom || $vente->client_telephone)
                    <p style="margin-top: 10px;"><strong>Client :</strong> {{ $vente->client_prenom ?? '' }} {{ $vente->client_nom ?? '' }}</p>
                    @if ($vente->client_telephone)
                        <p><strong>Téléphone :</strong> {{ $vente->client_telephone }}</p>
                    @endif
                @else
                    <p style="margin-top: 10px; color: #999;"><em>Vente anonyme</em></p>
                @endif
            </div>
        </div>

        @if ($vente->client_nom || $vente->client_prenom || $vente->client_telephone)
            <div class="client-info">
                <h4>👤 Informations du client</h4>
                @if ($vente->client_prenom || $vente->client_nom)
                    <p><strong>Nom :</strong> {{ $vente->client_prenom ?? '' }} {{ $vente->client_nom ?? '' }}</p>
                @endif
                @if ($vente->client_telephone)
                    <p><strong>Téléphone :</strong> {{ $vente->client_telephone }}</p>
                @endif
            </div>
        @endif

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Désignation</th>
                        <th class="quantite">Quantité</th>
                        <th class="prix">Prix unitaire</th>
                        <th class="prix">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vente->lignes as $ligne)
                        <tr>
                            <td>
                                @if ($ligne->produit_id)
                                    <strong>{{ $ligne->produit->nom ?? 'Produit supprimé' }}</strong>
                                    <br>
                                    <small style="color: #999;">{{ $ligne->unite->type_unite ?? '—' }}</small>
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

        <div class="total-section">
            <div class="total-box">
                <div class="total-ligne">
                    <span class="total-ligne-label">Montant HT :</span>
                    <span>{{ number_format($vente->montant_total, 0, ',', ' ') }} F</span>
                </div>
                <div class="total-ligne">
                    <span class="total-ligne-label">TVA (0%) :</span>
                    <span>0 F</span>
                </div>
                <div class="total-final">
                    <span>Total TTC :</span>
                    <span>{{ number_format($vente->montant_total, 0, ',', ' ') }} F</span>
                </div>
            </div>
        </div>

        <div class="mentions">
            <strong>🔔 Important :</strong><br>
            • Facture établie à titre de preuve de paiement<br>
            • Aucun droit de rétractation après achat<br>
            • Pour toute réclamation, conservez cette facture
        </div>

        <div class="pied-page">
            <p style="margin-bottom: 8px;">✓ Merci pour votre achat !</p>
            <p>Vision Moderne Construction SARL | Tous droits réservés</p>
            <p style="font-size: 11px; margin-top: 10px;">Facture générée le {{ now()->format('d/m/Y à H:i:s') }}</p>
        </div>
    </div>

    <script>
        if (window.location.pathname.includes('/facture/')) {
            try {
                localStorage.removeItem('panier_caisse');
            } catch (e) {
                console.warn('Panier non effacé côté client:', e);
            }
        }
    </script>
</body>
</html>
