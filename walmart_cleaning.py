import pandas as pd
from sqlalchemy import create_engine

# =========================
# 1. LOAD DATA
# =========================
df = pd.read_csv(r"C:\Users\czarina\Downloads\midterm\data\Walmart.csv")

# =========================
# 2. HANDLE MISSING VALUES
# =========================
df = df.dropna()

# =========================
# 3. REMOVE DUPLICATES (VERY IMPORTANT)
# =========================
df = df.drop_duplicates(subset=["invoice_id"])

# =========================
# 4. CHECK COLUMN NAME SAFETY
# =========================
print("Columns in dataset:", df.columns)

# =========================
# 5. OUTLIER REMOVAL (ONLY IF 'sales' EXISTS)
# =========================
if "sales" in df.columns:
    Q1 = df["sales"].quantile(0.25)
    Q3 = df["sales"].quantile(0.75)
    IQR = Q3 - Q1

    lower_bound = Q1 - 1.5 * IQR
    upper_bound = Q3 + 1.5 * IQR

    df = df[(df["sales"] >= lower_bound) & (df["sales"] <= upper_bound)]

    # =========================
    # 6. NORMALIZATION
    # =========================
    df["sales_normalized"] = (
        (df["sales"] - df["sales"].min()) /
        (df["sales"].max() - df["sales"].min())
    )
else:
    print("⚠ 'sales' column not found. Skipping outlier + normalization.")

# =========================
# 7. SAVE CLEANED CSV
# =========================
df.to_csv(r"C:\Users\czarina\Downloads\midterm\data\cleaned_dataset.csv", index=False)

print("Cleaned dataset saved successfully.")
print("Remaining rows:", len(df))

# =========================
# 8. MYSQL CONNECTION (XAMPP)
# =========================
engine = create_engine("mysql+pymysql://root:@localhost/walmart_sales")

# =========================
# 9. EXPORT TO MYSQL (REPLACE TABLE CLEANLY)
# =========================
df.to_sql(
    "walmart_sales",
    con=engine,
    if_exists="replace",
    index=False
)

print("Data successfully imported into MySQL table: walmart_sales")