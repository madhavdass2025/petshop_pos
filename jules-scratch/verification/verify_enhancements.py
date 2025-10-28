from playwright.sync_api import sync_playwright, expect
import subprocess

def run(playwright):
    # Manually insert data
    subprocess.run('mysql -u root -pdva medical_pos -e "INSERT INTO sales_returns (invoice_id, reason, total_refund_amount) VALUES (1, \'Test Return\', 50.00);"', shell=True)
    subprocess.run('mysql -u root -pdva medical_pos -e "INSERT INTO sales_return_items (return_id, stock_id, quantity, refund_amount) VALUES (LAST_INSERT_ID(), 1, 1, 50.00);"', shell=True)

    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    # Sales Return Report
    page.goto("http://localhost:8000/sales_return_report.php")
    expect(page.locator("h1:has-text('Sales Return Report')")).to_be_visible()
    page.screenshot(path="jules-scratch/verification/sales_return_report.png")

    # Stock Level Report
    page.goto("http://localhost:8000/stock_level_report.php")
    expect(page.locator("h1:has-text('Stock Level Report')")).to_be_visible()
    page.screenshot(path="jules-scratch/verification/stock_level_report.png")

    # POS Page
    page.goto("http://localhost:8000/pos.php")
    expect(page.locator("h2:has-text('Point of Sale')")).to_be_visible()
    page.screenshot(path="jules-scratch/verification/pos_enhancements.png")

    # Invoice View
    page.goto("http://localhost:8000/view_sales_invoice.php?id=1")
    expect(page.locator("h2:has-text('Invoice Details')")).to_be_visible()
    page.screenshot(path="jules-scratch/verification/invoice_view_enhancements.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)
