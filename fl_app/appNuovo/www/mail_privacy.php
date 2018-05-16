<?php
$email = filter_var($_REQUEST['email'], FILTER_SANITIZE_EMAIL);
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    /* Configurazione Casella Mail */
    define("mail_host", "smtps.aruba.it");
    define("mail_user", "noreply@fordauthos.com");
    define("mail_password", "Authos2018");

    define("mail_nameFrom", "Authos S.p.A");
    define("mail_from", 'servizioclienti@authos.it');
    define("reply_to", 'servizioclienti@authos.it');
    define("mail_admin", "giuseppe.nacci@gmail.com"); //Amministratore del gestionale
    function smail($destinatario, $soggetto, $messaggio, $from = '', $nameFrom = '', $allegato = '', $allname = '')
    {

         require_once '../../../fl_set/librerie/PHPMailer/PHPMailerAutoload.php';

        $mail = new PHPMailer;
        //$mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->Host = mail_host;
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->Username = mail_user;
        $mail->Password = mail_password;
        $mail->CharSet = "UTF-8";

        $mail->addAddress($destinatario, $destinatario);
        $mail->Subject = $soggetto;
        $mail->Body = $messaggio;
        $mail->AltBody = 'To view this email please enable HTML';
        if ($allegato != '') {
            $mail->addAttachment($allegato, $allname);
        }

        if ($from != '') {$mail->setFrom($from, $nameFrom);} else { $mail->setFrom(mail_from, mail_nameFrom);}
        ($from != '') ? $mail->addReplyTo($from, $nameFrom) : $mail->addReplyTo(mail_from, mail_nameFrom);

        if (!$mail->send()) {
            mail(mail_admin, '..:: Errore funzione smail', $mail->ErrorInfo);
            $mail->clearAddresses();
            $mail->clearAttachments();
            return $mail->ErrorInfo;
        } else {
            $mail->clearAddresses();
            $mail->clearAttachments();
            return true;
        }

    }

    $headers = "From: noreply@fordauthos.com" . "\r\n";
    $message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
    <html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <title></title>
        <meta name="generator" content="LibreOffice 5.1.6.2 (Linux)"/>
        <meta name="author" content="Utente Windows"/>
        <meta name="created" content="2018-05-10T13:34:00"/>
        <meta name="changedby" content="Utente Windows"/>
        <meta name="changed" content="2018-05-10T13:35:00"/>
        <meta name="AppVersion" content="16.0000"/>
        <meta name="DocSecurity" content="0"/>
        <meta name="HyperlinksChanged" content="false"/>
        <meta name="LinksUpToDate" content="false"/>
        <meta name="ScaleCrop" content="false"/>
        <meta name="ShareDoc" content="false"/>
        <style type="text/css">
            @page { margin-left: 0.79in; margin-right: 0.79in; margin-top: 0.98in; margin-bottom: 0.79in }
            p { margin-bottom: 0.1in; direction: ltr; line-height: 120%; text-align: left; orphans: 2; widows: 2 }
            a:link { color: #0000ff }
        </style>
    </head>
    <body lang="it-IT" link="#0000ff" dir="ltr">
    <p style="margin-top: 0.1in; margin-bottom: 0.1in; line-height: 100%"><a name="_GoBack"></a>
    <font face="inherit, serif"><font size="4" style="font-size: 13pt">INFORMATIVA
    PER IL TRATTAMENTO DI DATI</font></font></p>
    <p style="margin-bottom: 0in; line-height: 100%"><font face="Times New Roman, serif"><font size="3" style="font-size: 12pt"><br/>
    In
    questa pagina si descrivono le modalità di gestione del sito in
    riferimento al trattamento dei dati personali degli utenti che lo
    consultano, nonché modalità e finalità di trattamento dei dati
    personali.<br/>
    <br/>
    Si tratta di un’informativa resa anche ai
    sensi del\'art.13 del D.lgs 196/2003 a coloro che interagiscono con i
    servizi web accessibili per via telematica a partire dall’indirizzo:
    www.authos.it.<br/>
    <br/>
    L\'informativa è resa solo per il sito in
    oggetto e non anche per altri siti Web eventualmente consultabili
    tramite i nostri links, di cui Authos Spa non è in alcun modo
    responsabile.<br/>
    &nbsp;</font></font></p>
    <p style="margin-top: 0.1in; margin-bottom: 0.1in; line-height: 100%">
    <font face="inherit, serif"><font size="4" style="font-size: 13pt">TIPI
    DI DATI TRATTATI</font></font></p>
    <p style="margin-bottom: 0in; line-height: 100%"><font face="Times New Roman, serif"><font size="3" style="font-size: 12pt"><br/>
    </font></font><font face="Times New Roman, serif"><font size="3" style="font-size: 12pt"><b>Dati
    di Navigazione</b></font></font><font face="Times New Roman, serif"><font size="3" style="font-size: 12pt"><br/>
    <br/>
    I
    sistemi informatici e le procedure software preposte al funzionamento
    di questo sito web acquisiscono, nel corso del loro normale
    esercizio, alcuni dati personali la cui trasmissione è implicita
    nell\'uso dei protocolli di comunicazione di Internet. Si tratta
    d\'informazioni che non sono raccolte per essere associate ad
    interessati identificati, ma che per loro stessa natura potrebbero,
    attraverso elaborazioni ed associazioni con dati detenuti da terzi,
    permettere di identificare gli utenti. In questa categoria di dati
    rientrano gli indirizzi IP o i nomi a dominio dei computer utilizzati
    dagli utenti che si connettono al sito, gli indirizzi in notazione
    URI (Uniform Resource Identifier) delle risorse richieste, l\'orario
    della richiesta, il metodo utilizzato nel sottoporre la richiesta al
    server, la dimensione del file ottenuto in risposta, il codice
    numerico indicante lo stato della risposta data dal server (buon
    fine, errore, ecc.) ed altri parametri relativi al sistema operativo
    e all\'ambiente informatico dell\'utente. Questi dati sono utilizzati
    al solo fine di ricavare informazioni statistiche anonime sull\'uso
    del sito e per controllarne il corretto funzionamento e vengono
    cancellati immediatamente dopo l\'elaborazione. I dati potrebbero
    essere utilizzati per l\'accertamento di responsabilità in caso di
    ipotetici reati informatici ai danni del sito.<br/>
    <br/>
    </font></font><font face="Times New Roman, serif"><font size="3" style="font-size: 12pt"><b>Dati
    forniti volontariamente dall\'utente</b></font></font><font face="Times New Roman, serif"><font size="3" style="font-size: 12pt"><br/>
    <br/>
    L\'invio
    facoltativo, esplicito e volontario di posta elettronica agli
    indirizzi indicati su questo sito, così come la compilazione dei
    form per l’invio di richieste specifiche (adesione a newsletter,
    richieste di preventivi ed offerte, ecc) comportano la successiva
    acquisizione dell\'indirizzo del mittente, necessario per rispondere
    alle richieste, nonché degli eventuali altri dati personali inseriti
    nella missiva o nel form.<br/>
    <br/>
    Specifiche informative di
    sintesi verranno progressivamente riportate o visualizzate nelle
    pagine del sito predisposte per particolari servizi a richiesta.<br/>
    &nbsp;</font></font></p>
    <p style="margin-top: 0.1in; margin-bottom: 0.1in; line-height: 100%">
    <font face="inherit, serif"><font size="4" style="font-size: 13pt">CONSEGUENZE
    DEL RIFIUTO DI CONFERIMENTO DEI DATI</font></font></p>
    <p style="margin-bottom: 0in; line-height: 100%"><font face="Times New Roman, serif"><font size="3" style="font-size: 12pt"><br/>
    A
    parte quanto specificato per i dati di navigazione, l\'utente è
    libero di fornire i dati personali riportati nei moduli di richiesta
    o comunque indicati in contatti per sollecitare l\'invio di materiale
    informativo o di altre comunicazioni. Il loro mancato conferimento
    può comportare l\'impossibilità di ottenere quanto richiesto.<br/>
    &nbsp;</font></font></p>
    <p style="margin-top: 0.1in; margin-bottom: 0.1in; line-height: 100%">
    <font face="inherit, serif"><font size="4" style="font-size: 13pt">MODALITA\'
    DEL TRATTAMENTO</font></font></p>
    <p style="margin-bottom: 0in; line-height: 100%"><font face="Times New Roman, serif"><font size="3" style="font-size: 12pt"><br/>
    I
    dati vengono trattati principalmente con strumenti elettronici e
    informatici e memorizzati sia su supporti informatici che su supporti
    cartacei che su ogni altro tipo di supporto idoneo, nel rispetto
    delle misure minime di sicurezza ai sensi del Disciplinare Tecnico in
    materia di misure minime di sicurezza, Allegato B del Codice della
    Privacy. Specifiche misure di sicurezza sono osservate per prevenire
    la perdita di dati, usi illeciti o non corretti ed accessi non
    autorizzati.<br/>
    <br/>
    La informiamo che, per fornire un servizio
    completo il nostro portale contiene link ad altri siti web, non
    gestiti da noi. Non siamo responsabili di errori, contenuti, cookies,
    pubblicazioni di contenuto morale illecito, pubblicità, banner o
    files non conformi alle disposizioni normative vigenti e del rispetto
    della normativa Privacy da parte di siti da noi gestiti a cui si fa
    riferimento. Per migliorare il servizio offerto è gradita una
    immediata segnalazione di malfunzionamenti, abusi o suggerimenti
    all’indirizzo di posta elettronica: info@authos.it<br/>
    &nbsp;</font></font></p>
    <p style="margin-top: 0.1in; margin-bottom: 0.1in; line-height: 100%">
    <font face="inherit, serif"><font size="4" style="font-size: 13pt">FINALITA’
    DEL TRATTAMENTO</font></font></p>
    <p style="margin-bottom: 0in; line-height: 100%"><font face="Times New Roman, serif"><font size="3" style="font-size: 12pt"><br/>
    Il
    trattamento dei dati verrà effettuato per:<br/>
    - dare la
    possibilità di accedere alle sezioni pubbliche e/o riservate del
    sito;<br/>
    - dare esecuzione all\'attivazione e alla manutenzione di
    eventuali servizi sottoscritti on-line;<br/>
    - per la gestione di
    ordini di acquisto;<br/>
    - elaborazione e archiviazione a scopo di
    redazione dei documenti contabili;<br/>
    - eseguire gli obblighi
    previsti da leggi o regolamenti;<br/>
    - la tutela del Titolare in
    sede giudiziaria;<br/>
    - inviare materiale informativo di natura
    tecnica, amministrativa, commerciale o promozionale;<br/>
    -
    consentire il monitoraggio costante sull\'efficacia del servizio
    proposto.<br/>
    -&nbsp;elaborazione e archiviazione a scopo di
    webmarketing.<br/>
    &nbsp;</font></font></p>
    <p style="margin-top: 0.1in; margin-bottom: 0.1in; line-height: 100%">
    <font face="inherit, serif"><font size="4" style="font-size: 13pt">DIRITTI
    DEGLI INTERESSATI</font></font></p>
    <p style="margin-bottom: 0in; line-height: 100%"><font face="Times New Roman, serif"><font size="3" style="font-size: 12pt"><br/>
    In
    relazione al trattamento di dati personali, ai sensi dell\'art. 7
    (Diritto di accesso ai dati personali ed altri diritti) del Codice
    della Privacy:<br/>
    1. L\'interessato ha diritto di ottenere la
    conferma dell\'esistenza o meno di dati personali che lo riguardano,
    anche se non ancora registrati, e la loro comunicazione in forma
    intelligibile.<br/>
    2. L\'interessato ha diritto di ottenere
    l\'indicazione:<br/>
    a) dell\'origine dei dati personali;<br/>
    b) delle
    finalità e modalità del trattamento;<br/>
    c) della logica applicata
    in caso di trattamento effettuato con l\'ausilio di strumenti
    elettronici;<br/>
    d) degli estremi identificativi del titolare, dei
    responsabili e del rappresentante designato ai sensi dell\'articolo 5,
    comma 2;<br/>
    e) dei soggetti o delle categorie di soggetti ai quali
    i dati personali possono essere comunicati o che possono venirne a
    conoscenza in qualità di rappresentante designato nel territorio
    dello Stato, di responsabili o incaricati.<br/>
    3. L\'interessato ha
    diritto di ottenere:<br/>
    a) l\'aggiornamento, la rettificazione
    ovvero, quando vi ha interesse, l\'integrazione dei dati;<br/>
    b) la
    cancellazione, la trasformazione in forma anonima o il blocco dei
    dati trattati in violazione di legge, compresi quelli di cui non è
    necessaria la conservazione in relazione agli scopi per i quali i
    dati sono stati raccolti o successivamente trattati;<br/>
    c)
    l\'attestazione che le operazioni di cui alle lettere a) e b) sono
    state portate a conoscenza, anche per quanto riguarda il loro
    contenuto, di coloro ai quali i dati sono stati comunicati o diffusi,
    eccettuato il caso in cui tale adempimento si riveli impossibile o
    comporti un impiego di mezzi manifestamente sproporzionato rispetto
    al diritto tutelato.<br/>
    4. L\'interessato ha diritto di opporsi, in
    tutto o in parte:<br/>
    a) per motivi legittimi al trattamento dei
    dati personali che lo riguardano, ancorché pertinenti allo scopo
    della raccolta;<br/>
    b) al trattamento di dati personali che lo
    riguardano a fini di invio di materiale pubblicitario o di vendita
    diretta o per il compimento di ricerche di mercato o di comunicazione
    commerciale.<br/>
    &nbsp;</font></font></p>
    <p style="margin-top: 0.1in; margin-bottom: 0.1in; line-height: 100%">
    <font face="inherit, serif"><font size="4" style="font-size: 13pt">IL
    TITOLARE DEL TRATTAMENTO</font></font></p>
    <p style="margin-bottom: 0in; line-height: 100%"><font face="Times New Roman, serif"><font size="3" style="font-size: 12pt"><br/>
    Titolare
    del trattamento è Authos Spa, con sede legale in Torino, C.so
    Grosseto 318.<br/>
    <br/>
    Per esercitare i diritti previsti all\'art. 7
    del Codice della Privacy, sopra elencati, l\'interessato dovrà
    rivolgere richiesta scritta attraverso e-mail. L\'e-mail va
    indirizzata a: info@authos.it<br/>
    <br/>
    L’elenco dei responsabili
    del trattamento dei dati e dei responsabili del trattamento in
    outsourcing è consultabile presso la nostra sede sita in C.so
    Grosseto 318 - Torino, dalle ore 10.00 alle ore 12.00 nelle giornate
    di lunedì e venerdì.</font></font></p>
    <p style="margin-bottom: 0.11in; line-height: 108%"><br/>
    <br/>
    
    </p>
    </body>
    </html>';
     echo smail($email, 'Informativa privacy Authos S.p.A.', $message);
     header("HTTP/1.1 200 OK");

}
