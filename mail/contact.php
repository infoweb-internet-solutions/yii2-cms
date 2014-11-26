testtest
<?= Yii::t('frontend', 'Beste') . ' [name]' ?>
<br><br>
<?= Yii::t('frontend', 'Er is een nieuwe contactaanvraag met onderstaande gegevens') ?>
<br><br>
<table style="margin: 0;margin-bottom: 50px;width: 100%;font-size: 12px;font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.6; padding: 20px;border-collapse: collapse;border: none;">
    <!--
    <thead>
        <tr>
            <th style="width: 50%;border-bottom: 1px solid #EEEEEE;padding: 8px;font-weight: bold;text-align: left;"><?= Yii::t('app', 'Veld') ?></th>
            <th style="width: 50%;border-bottom: 1px solid #EEEEEE;padding: 8px;font-weight: bold;text-align: left;"><?= Yii::t('app', 'Waarde') ?></th>
        </tr>
    </thead>
    -->
    <tbody>
        <tr>
            <td style="border-bottom: 1px solid #EEEEEE;padding: 8px;font-weight: bold;"><?= Yii::t('frontend', 'Voornaam') ?></td>
            <td style="border-bottom: 1px solid #EEEEEE;padding: 8px;"><?= $post->firstName ?></td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #EEEEEE;padding: 8px;font-weight: bold;"><?= Yii::t('frontend', 'Naam') ?></td>
            <td style="border-bottom: 1px solid #EEEEEE;padding: 8px;"><?= $post->name ?></td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #EEEEEE;padding: 8px;font-weight: bold;"><?= Yii::t('frontend', 'Telefoon') ?></td>
            <td style="border-bottom: 1px solid #EEEEEE;padding: 8px;"><?= $post->phone ?></td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #EEEEEE;padding: 8px;font-weight: bold;"><?= Yii::t('frontend', 'E-mail') ?></td>
            <td style="border-bottom: 1px solid #EEEEEE;padding: 8px;"><?= $post->email ?></td>
        </tr>
        <tr>
            <td colspan="2" style="border-bottom: 1px solid #EEEEEE;padding: 8px;font-weight: bold;"><?= Yii::t('frontend', 'Bericht') ?></td>
        </tr>
        <tr>
            <td colspan="2" style="border-bottom: 1px solid #EEEEEE;padding: 8px;"><?= nl2br($post->body) ?></td>
        </tr>
    </tbody>
</table>