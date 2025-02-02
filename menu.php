<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Best Restaurant</title>
  <link rel="stylesheet" href="menu.css">
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="footer.css">
  <script defer src="script.js"></script>
</head>

<body>
     <?php include('header.php') ?>
        
    <section class="hero">
        <div class="hero-content">
            <p> Fresh food with traditional and modern variety</p>
        </div>
    </section>

    <main>
        <div id="menu">
            <h1 id="section_menu">Menu</h1>
            <div id="menu_row">
                <!-- Kategoritë do të shtohen këtu -->
            </div>
        </div>

    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch("menuItem.php?action=getMenuItems")
                .then(response => response.json())
                .then(data => {
                    const menuRow = document.getElementById("menu_row");
                    // Krijo një objekt për të grupuar artikujt sipas kategorive
                    const categories = {};

                    data.forEach(item => {
                        if (!categories[item.Category]) {
                            categories[item.Category] = [];
                        }
                        categories[item.Category].push(item);
                    });

                    // Krijo strukturën për secilën kategori
                    for (const category in categories) {
                        const categoryHtml = `
                            <div id="menu_col">
                                <h2>${category}</h2>
                                ${categories[category].map(item => `
                                    <div class="box-menu">
                                        <div id="image">
                                            <img src="${item.ImagePath}" alt="${item.Name}">
                                        </div>
                                        <div>
                                            <h3>${item.Name}</h3>
                                            <h4>${item.Price}€</h4>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        `;

                        if (category === "Drinks") {
                            drinksRow.innerHTML += categoryHtml;
                        } else {
                            menuRow.innerHTML += categoryHtml;
                        }
                    }
                })
                .catch(error => console.error("Error fetching menu items:", error));
        });
    </script>

    <?php include ('footer.php') ?>
</body>
</html>