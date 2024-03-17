

const getAllNews = () => {
    $.ajax({
        url: "http://localhost/news_website/backend/api.php",
        method: "GET",
        dataType: "json",
        success: function(data) {
            displayNews(data);
        },
        error: function(error) {
            console.error(error);
        }
    });
};

const displayNews = (data) => {
    $("#news-div").empty();
    $.each(data.news, function(index, newsItem) {
        const newsDiv = $("<div></div>");
        const newsType = $("<p></p>").text(newsItem.type);
        const newsText = $("<p></p>").text(newsItem.text);
        newsDiv.append(newsType)
        newsDiv.append(newsText)

        $("#news-div").append(newsDiv);
    });
};

$("#news-form").on("submit", function(e) {
    e.preventDefault();
    addNew();
});

const addNew = () => {
    const formData = new FormData();
    formData.append("text", $('#news-text').val());
    formData.append("type", $('#news-type').val());

    $.ajax({
        url: "http://localhost/news_website/backend/api.php",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function() {
            $('#news-type').val("");
            $('#news-text').val("");
            getAllNews();
        },
        error: function(error) {
            console.error(error);
        }
    });
};



getAllNews();
