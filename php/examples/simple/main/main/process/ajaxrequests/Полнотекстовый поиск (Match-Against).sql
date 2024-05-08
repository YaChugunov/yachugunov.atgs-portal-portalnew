(
    SELECT
        'mail_inbox' AS type,
        ID AS id,
        koddocmail AS kod,
        inbox_docID AS par1,
        inbox_docDate AS par2,
        inbox_docSender AS par3,
        inbox_docSender_kodzakaz AS par4,
        inbox_docAbout AS par5,
        inbox_docRecipientSTR AS par6,
        inbox_docContractorSTR AS par7,
        MATCH (inbox_docAbout, inbox_docSender) AGAINST ('{$searchstring}' IN BOOLEAN MODE) AS rel
    FROM
        mailbox_incoming
    WHERE
        MATCH (inbox_docAbout, inbox_docSender) AGAINST ('{$searchstring}' IN BOOLEAN MODE)
)
UNION
(
    SELECT
        'mail_outbox' AS type,
        ID AS id,
        koddocmail AS kod,
        outbox_docID AS par1,
        outbox_docDate AS par2,
        outbox_docRecipient AS par3,
        outbox_docRecipient_kodzakaz AS par4,
        outbox_docAbout AS par5,
        outbox_docSenderSTR AS par6,
        outbox_docContractorSTR AS par7,
        MATCH (outbox_docAbout, outbox_docRecipient) AGAINST ('{$searchstring}' IN BOOLEAN MODE) as rel
    FROM
        mailbox_outgoing
    WHERE
        MATCH (outbox_docAbout, outbox_docRecipient) AGAINST ('{$searchstring}' IN BOOLEAN MODE)
)
UNION
(
    SELECT
        'dog_base' AS type,
        ID AS id,
        koddoc AS kod,
        docnumber AS par1,
        yearnachdoc AS par2,
        'empty' AS par3,
        'empty' AS par4,
        docnameshot AS par5,
        docnamefullm AS par6,
        'empty' AS par7,
        MATCH (docnameshot, docnamefullm) AGAINST ('{$searchstring}' IN BOOLEAN MODE) as rel
    FROM
        dognet_docbase
    WHERE
        MATCH (docnameshot, docnamefullm) AGAINST ('{$searchstring}' IN BOOLEAN MODE)
)
UNION
(
    SELECT
        'dog_object' AS type,
        ID AS id,
        kodobject AS kod,
        'empty' AS par1,
        'empty' AS par2,
        'empty' AS par3,
        'empty' AS par4,
        nameobjectshot AS par5,
        nameobjectlong AS par6,
        'empty' AS par7,
        MATCH (nameobjectshot, nameobjectlong) AGAINST ('{$searchstring}' IN BOOLEAN MODE) as rel
    FROM
        dognet_spobject
    WHERE
        MATCH (nameobjectshot, nameobjectlong) AGAINST ('{$searchstring}' IN BOOLEAN MODE)
)
UNION
(
    SELECT
        'contragent' AS type,
        id AS id,
        kodcontragent AS kod,
        'empty' AS par1,
        'empty' AS par2,
        'empty' AS par3,
        'empty' AS par4,
        nameshort AS par5,
        namefull AS par6,
        'empty' AS par7,
        MATCH (nameshort, namefull) AGAINST ('{$searchstring}' IN BOOLEAN MODE) as rel
    FROM
        sp_contragents
    WHERE
        MATCH (nameshort, namefull) AGAINST ('{$searchstring}' IN BOOLEAN MODE)
)
ORDER BY
    rel DESC
LIMIT
    500 ";