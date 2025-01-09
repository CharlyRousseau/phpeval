<?php

namespace App\Entity\Emprunt;

class Emprunt
{
    private $id;
    private $id_utilisateur;
    private $id_livre;
    private $date_emprunt;
    private $date_retour_prevue;
    private $date_retour_effectif;
    private $statut;

    // Constructeur
    public function __construct($id_utilisateur, $id_livre)
    {
        $this->id_utilisateur = $id_utilisateur;
        $this->id_livre = $id_livre;
        $this->date_emprunt = date("Y-m-d H:i:s");
        $this->date_retour_prevue = date("Y-m-d", strtotime("+30 days")); // Retour prévu dans 30 jours
        $this->date_retour_effectif = null;
        $this->statut = "en cours";
    }

    // Getters et Setters
    public function getId()
    {
        return $this->id;
    }

    public function getIdUtilisateur()
    {
        return $this->id_utilisateur;
    }

    public function getIdLivre()
    {
        return $this->id_livre;
    }

    public function getDateEmprunt()
    {
        return $this->date_emprunt;
    }

    public function getDateRetourPrevue()
    {
        return $this->date_retour_prevue;
    }

    public function getDateRetourEffectif()
    {
        return $this->date_retour_effectif;
    }

    public function getStatut()
    {
        return $this->statut;
    }

    // Méthode pour enregistrer l'emprunt dans la base de données
    public function enregistrer($pdo)
    {
        $query = "INSERT INTO emprunts (id_utilisateur, id_livre, date_emprunt, date_retour_prevue, statut)
            VALUES (:id_utilisateur, :id_livre, :date_emprunt, :date_retour_prevue, :statut)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ":id_utilisateur" => $this->id_utilisateur,
            ":id_livre" => $this->id_livre,
            ":date_emprunt" => $this->date_emprunt,
            ":date_retour_prevue" => $this->date_retour_prevue,
            ":statut" => $this->statut,
        ]);
        $this->id = $pdo->lastInsertId();
    }

    // Méthode pour retourner un livre
    public function retourner($pdo)
    {
        $this->date_retour_effectif = date("Y-m-d");
        $this->statut = "retourné";

        $query =
            "UPDATE emprunts SET date_retour_effectif = ?, statut = ? WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$this->date_retour_effectif, $this->statut, $this->id]);
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    // Méthode pour obtenir l'historique des emprunts d'un utilisateur
    public static function getHistorique($pdo, $id_utilisateur)
    {
        $query =
            "SELECT * FROM emprunts WHERE id_utilisateur = ? ORDER BY date_emprunt DESC";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_utilisateur]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Méthode pour vérifier si un livre est déjà emprunté
    public static function estLivreEmprunte($pdo, $id_livre)
    {
        $query =
            "SELECT * FROM emprunts WHERE id_livre = ? AND statut = 'en cours'";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_livre]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
?>
