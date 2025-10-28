from playwright.sync_api import sync_playwright, expect
import subprocess

def run(playwright):
    # Manually insert data
    subprocess.run('mysql -u root -pdva medical_pos -e "INSERT INTO customers (name, phone) VALUES (\'John Doe\', \'1234567890\'); SET @owner_id = LAST_INSERT_ID(); INSERT INTO pets (owner_id, name, species, breed) VALUES (@owner_id, \'Fido\', \'Dog\', \'Golden Retriever\'); SET @pet_id = LAST_INSERT_ID(); INSERT INTO sales_invoices (pet_id, total_amount, total_gst, discount_amount, status, veterinarian_name) VALUES (@pet_id, 100, 10, 5, \'Paid\', \'Dr. Smith\');"', shell=True)

    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    # Owners Page
    page.goto("http://localhost:8000/owners.php")
    expect(page.locator("h1:has-text('Pet Owners')")).to_be_visible()
    page.screenshot(path="jules-scratch/verification/owners_page.png")

    # Pets Page
    page.goto("http://localhost:8000/pets.php")
    expect(page.locator("h1:has-text('Pets')")).to_be_visible()
    page.screenshot(path="jules-scratch/verification/pets_page.png")

    # POS Page
    page.goto("http://localhost:8000/pos.php")
    expect(page.locator("h2:has-text('Point of Sale')")).to_be_visible()
    page.screenshot(path="jules-scratch/verification/pos_page.png")

    # Sales Report
    page.goto("http://localhost:8000/sales_report.php")
    expect(page.locator("h1:has-text('Sales Report')")).to_be_visible()
    page.screenshot(path="jules-scratch/verification/sales_report.png")

    # Invoice View
    page.goto("http://localhost:8000/view_sales_invoice.php?id=1")
    expect(page.locator("h2:has-text('Invoice Details')")).to_be_visible()
    page.screenshot(path="jules-scratch/verification/invoice_view.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)
