FROM python:3.11-slim

# Установка компилятора и зависимостей для scipy
RUN apt-get update && apt-get install -y \
    build-essential \
    gcc \
    g++ \
    python3-dev \
    libatlas-base-dev \
    liblapack-dev \
    gfortran \
    cmake \
    pkg-config \
    libopenblas-dev \
    && apt-get clean

# Установка pip и setuptools
RUN pip install --upgrade pip setuptools

# Копирование requirements.txt и установка зависимостей
COPY requirements.txt /app/requirements.txt
WORKDIR /app
RUN pip install --no-cache-dir -r requirements.txt

# Копируем локально загруженные данные NLTK
COPY nltk_data /root/nltk_data

# Копирование остальных файлов приложения
COPY . /app

# Перепроверка наличия нужных ресурсов NLTK
RUN python -c "import nltk; nltk.data.path.append('/root/nltk_data'); nltk.download('punkt', download_dir='/root/nltk_data'); nltk.download('punkt_tab', download_dir='/root/nltk_data'); nltk.download('stopwords', download_dir='/root/nltk_data'); nltk.download('wordnet', download_dir='/root/nltk_data')"

# Запуск приложения
CMD ["python", "main.py"]
