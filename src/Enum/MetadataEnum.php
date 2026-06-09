<?php

namespace App\Enum;

enum MetadataEnum: string
{
    case DATE_ARCHIVAGE = 'date_archivage';
    case DATE_DOC = 'date_doc';
    case DATE_ECHEANCE = 'date_echeance';
    case DATE_RECEPTION = 'date_reception';
    case DOC_STATUT = 'doc_statut';
    case DOMAIN = 'domaine';
    case FACTURE = 'facture';
    case FOURNISSEUR = 'fournisseur';
    case MONTANT_HT = 'montant_HT';
    case MONTANT_TTC = 'montant_ttc';
    case MONTANT_TVA = 'montant_TVA';
    case NUM_AVOIR = 'num_avoir';
    case NUM_BON = 'num_bon';
    case NUM_COMMANDE = 'num_commande';
    case NUM_FACTURE = 'num_facture';
    case NUM_FOURNISSEUR = 'num_fournisseur';
    case RAYON = 'rayon';
    case SECTEUR = 'secteur';
    case SOCIETE = 'societe';
    case SOURCE = 'source';
    case TYPE_DOC = 'type_doc';
}
