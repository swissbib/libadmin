USE libadmin;
SELECT institution.*,
postadresse.strasse AS postadresse_strasse,
postadresse.nummer AS postadresse_nummer,
postadresse.zusatz AS postadresse_zusatz,
postadresse.plz AS postadresse_plz,
postadresse.ort AS postadresse_ort,
postadresse.country AS postadresse_country,
postadresse.canton AS postadresse_canton,
postadresse.name_organisation AS postadresse_name_organisation,
kostenbeitrag.*,
kontakt.*,
rechnungsadresse.strasse AS rechnungsadresse_strasse,
rechnungsadresse.nummer AS rechnungsadresse_nummer,
rechnungsadresse.zusatz AS rechnungsadresse_zusatz,
rechnungsadresse.plz AS rechnungsadresse_plz,
rechnungsadresse.ort AS rechnungsadresse_ort,
rechnungsadresse.country AS rechnungsadresse_country,
rechnungsadresse.canton AS rechnungsadresse_canton,
rechnungsadresse.name_organisation AS rechnungsadresse_name_organisation,
kontakt_rechnung.name AS kontakt_rechnung_name,
kontakt_rechnung.vorname kontakt_rechnung_vorname,
kontakt_rechnung.anrede kontakt_rechnung_anrede,
kontakt_rechnung.email kontakt_rechnung_email,
admininstitution.name AS admin_institution_name,
`group`.label_de AS network
FROM institution
LEFT JOIN adresse AS postadresse ON institution.id_postadresse=postadresse.id
LEFT JOIN kostenbeitrag ON institution.id_kostenbeitrag=kostenbeitrag.id
LEFT JOIN kontakt ON institution.id_kontakt=kontakt.id
LEFT JOIN adresse AS rechnungsadresse ON institution.id_rechnungsadresse=rechnungsadresse.id
LEFT JOIN kontakt AS kontakt_rechnung ON institution.id_kontakt_rechnung=kontakt_rechnung.id
LEFT JOIN (`group` LEFT JOIN mm_institution_group_view on `group`.id=mm_institution_group_view.id_group)
ON institution.id = mm_institution_group_view.id_institution
LEFT JOIN (mn_institution_admininstitution LEFT JOIN admininstitution on mn_institution_admininstitution.id_admininstitution = admininstitution.id)
ON institution.id = mn_institution_admininstitution.id_institution
/* only the group from green, i.e. library network */
WHERE mm_institution_group_view.id_view=1