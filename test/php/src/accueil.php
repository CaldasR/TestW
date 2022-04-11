<script type="text/javascript" src="js/accueil.js"></script>
<link rel="stylesheet" href="css/accueil.css">

<p>Bonjour <?php echo getLogin($session); ?></p>
<button type="button" id="disconnect">Deconnecter</button>
<?php
	addJavascriptVar("isAdmin", getAdmin($session));
?>

<table id="providers">
    <caption><h2>Liste des fournisseurs</h2></caption>
    <thead>
        <tr>
            <th>Nom</th>
		    <th>Adresse</th>
		    <th>Pays</th>
        </tr>
    </thead>
    <tbody>
        <tr id="load">
            <td>Loading</td>
		    <td></td>
		    <td></td>
        </tr>
    </tbody>
</table>

<table id="companies">
    <caption><h2>Liste des entreprises</h2></caption>
    <thead>
        <tr>
            <th>Nom</th>
		    <th>Solde</th>
		    <th>Pays</th>
        </tr>
    </thead>
    <tbody>
        <tr id="load">
            <td>Loading</td>
		    <td></td>
		    <td></td>
        </tr>
    </tbody>
</table>

<table id="clients">
    <caption><h2>Liste des clients</h2></caption>
    <thead>
        <tr>
            <th>Nom</th>
		    <th>Adresse</th>
		    <th>Solde</th>
		    <th>Pays</th>
        </tr>
    </thead>
    <tbody>
        <tr id="load">
            <td>Loading</td>
		    <td></td>
		    <td></td>
		    <td></td>
        </tr>
    </tbody>
</table>

<table id="stocks">
    <caption><h2>Stocks des produits</h2></caption>
    <thead>
        <tr>
            <th>Nom du produit</th>
		    <th>Prix</th>
		    <th>Taxes %</th>
		    <th>Fournisseur / Compagnie</th>
		    <th>Stock</th>
        </tr>
    </thead>
    <tbody>
        <tr id="load">
            <td>Loading</td>
		    <td></td>
		    <td></td>
		    <td></td>
		    <td></td>
        </tr>
    </tbody>
</table>

<table id="transaction">
    <caption><h2>Transactions</h2></caption>
    <thead>
        <tr>
        	<th>Produit</th>
        	<th>Fournisseur → Compagnie</th>
        	<th>Compagnie → Client</th>
        	<th>Employé en charge</th>
        	<th>Prix unitaire</th>
        	<th>Nombre vendu</th>
        	<th>Date</th>
        </tr>
    </thead>
    <tbody>
        <tr id="load"><td>Loading</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    </tbody>
</table>

<div id="detailContainer" class="modalContainer hide">
	<div id="persoDetail" class='persoModal'>
		<?php if (getAdmin($session)) { ?>
			<div id='containerEdit'>
				<a class='pen'></a>
				<span>
					<span id='valid'>✓</span>
					<span id='refuse'>✗</span>
				</span>
			</div>
		<?php } ?>
		<a class="close-modal buttonClose">Close</a>

		<input id="id" name="id" type="hidden" value="">
		
		<h3>
			<span id='type'>Compagnie: </span>
			<span id='name' class='read'></span>
			<input type="text" id='input_name' class='edit' name="name"/>
		</h3>

		<p class='address'>
			<span>Adresse: </span>
			<span id='address' class='read'></span>
			<input type="text" id='input_address' class='edit' name="address"/>
		</p>

		<p class='balance'>
			<span>Solde: </span>
			<span id='balance' class='read'></span>
			<input type="text" id='input_balance' class='edit integerOnly' name="balance"/>
		</p>

		<div class='country'>
			<label>Pays: </label>
			<span id='country' class='read'></span>
			<select name="country" class='edit' id="input_country"></select>
		</div>

		<p class='price'>
			<span>Prix: </span>
			<span id='price' class='read'></span>
			<input type="text" id='input_price' class='edit' name="price"/>
		</p>

		<p class='tax'>
			<span>Taxes en %: </span>
			<span id='tax' class='read'></span>
			<input type="text" id='input_tax' class='edit' name="tax"/>
		</p>

		<table id="stocksDetails">
		    <caption><h4>Stocks</h4>
		    	<?php if (getAdmin($session)) { ?> <p id='newStock'>Nouveau stock</p> <?php } ?>
		    </caption>
		    <thead>
		        <tr><th>Nom du produit</th><th>Prix</th><th>Taxes %</th><th>Stock</th></tr>
		    </thead>
		    <tbody>
		        <tr id="load"><td>Loading</td><td></td><td></td><td></td></tr>
		    </tbody>
		</table>

		<table id="transactionsDetails">
		    <caption><h4>Transactions</h4>
		    	<?php if (getAdmin($session)) { ?> <p id='newTransaction'>Nouvelle transaction</p> <?php } ?>
	    	</caption>
		    <thead>
		        <tr>
		        	<th>Produit</th>
		        	<th id='swap'>Compagnie</th>
		        	<th>Employé en charge</th>
		        	<th>Prix unitaire</th>
		        	<th>Nombre vendu</th>
		        	<th>Date</th>
		        </tr>
		    </thead>
		    <tbody>
		        <tr id="load"><td>Loading</td><td></td><td></td><td></td><td></td><td></td></tr>
		    </tbody>
		</table>

	</div>
</div>

<div id="stockContainer" class="modalContainer hide">
	<div id="persoStock" class='persoModal'>
		<a class="close-modal buttonClose">Close</a>

		<p>Quel produit a ajouter ?</p>

		<div class='product'>
			<label>Produit: </label>
			<select name="product" id="input_product"></select>
		</div>

		<p class='quantity'>
			<span>Nombre a ajouter: </span>
			<input type="text" id='input_quantity' name="quantity" class='integerOnly' value='0'/>
		</p>

		<span>
			<span id='valid'>✓</span>
			<span id='refuse'>✗</span>
		</span>
	</div>
</div>


<div id="transactionContainer" class="modalContainer hide">
	<div id="persoTransaction" class='persoModal'>
		<a class="close-modal buttonClose">Close</a>
		<br>

		<div id='chooseProvider'>
			<label>Choix du fournisseur: </label>
			<select name="input_provider" id="input_provider"></select>
		</div>
		<div id='chooseCompany'>
			<label>Choix de la compagnie: </label>
			<select name="input_company" id="input_company"></select>
		</div>

		<div id='boxChoice'>
			<p>Produits disponibles: </p>
			<div id='containerCheckBox'>
			</div>
			<p class='quantityToBuy'>
				<span>Nombre a acheter: </span>
				<input type="text" id='input_quantity' name="quantity" class='integerOnly' value='1'/>
			</p>

			<p>Total: <span id='total'></span></p>

			<span id='canValid'>
				<span id='valid'>✓</span>
				<span id='refuse'>✗</span>
			</span>

			<p class='error hide'></p>
		</div>
		 
		
	</div>
</div>