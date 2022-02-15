//
//  ChannelList.swift
//  Streamly
//
//  Created by Rohit Singh on 6/25/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation

struct ChannelListResponse : Codable {
    
    let allGenre : [String]?
    let success  : Bool?
    let data     : [String:[ChannelList]]?
    
    enum CodingKeys: String, CodingKey {
        
        case allGenre = "allGenre"
        case success = "success"
        case data = "data"
        
    }
    
    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        allGenre = try values.decodeIfPresent([String].self, forKey: .allGenre)
        data = try values.decodeIfPresent([String:[ChannelList]].self, forKey: .data)
        success = try values.decodeIfPresent(Bool.self, forKey: .success)
        
    }
}

//struct ChannelCategory : Codable{
//
//    let Sports : [ChannelList]?
//    let News : [ChannelList]?
//    let Movies : [ChannelList]?
//    let Kids : [ChannelList]?
//
//
//    enum CodingKeys: String, CodingKey {
//
//        case Sports = "Sports"
//        case News = "News"
//        case Movies = "Movies"
//        case Kids = "Kids"
//    }
//
//    init(from decoder: Decoder) throws {
//        let values = try decoder.container(keyedBy: CodingKeys.self)
//        Sports = try values.decodeIfPresent([ChannelList].self, forKey: .Sports)
//        News = try values.decodeIfPresent([ChannelList].self, forKey: .News)
//        Movies = try values.decodeIfPresent([ChannelList].self, forKey: .Movies)
//        Kids = try values.decodeIfPresent([ChannelList].self, forKey: .Kids)
//
//
//    }
//}

struct ChannelList : Codable {
    
    //let genrename : [String]?
    var callSign:String?
    
    
    enum CodingKeys: String, CodingKey {
        
        
        case callSign = "callSign"
       // case genrename = "genrename"
        
    }
    
    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        //genrename = try values.decodeIfPresent([String].self, forKey: .genrename)
        callSign = try values.decodeIfPresent(String.self, forKey: .callSign)
        
        
    }
}



// MARK: - MychannelData
struct MychannelData: Codable {
    let success: Bool?
    let data: [MyChannel]?
    let message: String?
    let responseCode: Int?

    enum CodingKeys: String, CodingKey {
        case success, data, message
        case responseCode = "response_code"
    }
}

// MARK: - Datum
struct MyChannel: Codable {
    let id: Int?
    let name, callSign: String?
    let url: String?
    let genreID: Int?
    let genres: [String]?
    let image: String?
    let status: Int?
    let createdAt, updatedAt: String?
    let logo: String?
    let pivot: PivotCahnnel?

    enum CodingKeys: String, CodingKey {
        case id, name, callSign, url
        case genreID = "genre_id"
        case genres, image, status
        case createdAt = "created_at"
        case updatedAt = "updated_at"
        case logo, pivot
    }
}

// MARK: - Pivot
struct PivotCahnnel: Codable {
    let userID, channelsID: Int?
    let createdAt, updatedAt: String?

    enum CodingKeys: String, CodingKey {
        case userID = "user_id"
        case channelsID = "channels_id"
        case createdAt = "created_at"
        case updatedAt = "updated_at"
    }
}
