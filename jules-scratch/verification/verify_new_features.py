from playwright.sync_api import sync_playwright, expect

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()
    page.set_default_timeout(60000) # Increased timeout

    # Login
    page.goto("http://localhost:8000/index.php")
    page.fill("input[name='username']", "admin")
    page.fill("input[name='password']", "admin")
    page.click("button[type='submit']")
    expect(page).to_have_url("http://localhost:8000/dashboard.php")

    # POS Sale
    page.goto("http://localhost:8000/pos.php")
    product_search = page.locator("input#product_search")
    expect(product_search).to_be_visible()
    product_search.fill("Dolo 650")

    page.locator("text=Dolo 650").click()

    batch_select = page.locator("select#batch_select")
    expect(batch_select).to_be_visible()
    batch_select.select_option(label="DOLO123 - Exp: 2025-12-31")

    page.locator("input#quantity").fill("1")
    page.locator("button:has-text('Add to Cart')").click()

    page.locator("input[name='customer_name']").fill("Test Customer")
    page.locator("select[name='payment_method']").select_option("Card")
    page.locator("button:has-text('Complete Sale')").click()

    # Sales Report Verification
    page.goto("http://localhost:8000/sales_report.php")
    expect(page.locator("text=Test Customer")).to_be_visible()
    page.screenshot(path="jules-scratch/verification/sales_report.png")

    # Stock Report
    page.goto("http://localhost:8000/stock_report.php")
    expect(page.locator("h2:has-text('Stock Report')")).to_be_visible()
    page.screenshot(path="jules-scratch/verification/stock_report.png")

    # Sales Return
    # First, get the invoice ID from the sales report
    page.goto("http://localhost:8000/sales_report.php")
    invoice_id = page.locator("#sales_table tbody tr:first-child td:first-child").inner_text()

    # Now, go to the return page and use it
    page.goto("http://localhost:8000/add_sale_return.php")
    page.locator("input#invoice_id_search").fill(invoice_id)
    page.locator("button#search_invoice").click()
    expect(page.locator(f"h4:has-text('Invoice #{invoice_id}')")).to_be_visible()
    page.screenshot(path="jules-scratch/verification/sales_return.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)
