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
    let data     : ChannelCategory?
    
    enum CodingKeys: String, CodingKey {
        
        case allGenre = "allGenre"
        case success = "success"
        case data = "data"
        
    }
    
    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        allGenre = try values.decodeIfPresent([String].self, forKey: .allGenre)
        data = try values.decodeIfPresent(ChannelCategory.self, forKey: .data)
        success = try values.decodeIfPresent(Bool.self, forKey: .success)
        
    }
}

struct ChannelCategory : Codable{
    
    let Sports : [ChannelList]?
    let News : [ChannelList]?
    let Movies : [ChannelList]?
    let Kids : [ChannelList]?
    
    
    enum CodingKeys: String, CodingKey {
        
        case Sports = "Sports"
        case News = "News"
        case Movies = "Movies"
        case Kids = "Kids"
    }
    
    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        Sports = try values.decodeIfPresent([ChannelList].self, forKey: .Sports)
        News = try values.decodeIfPresent([ChannelList].self, forKey: .News)
        Movies = try values.decodeIfPresent([ChannelList].self, forKey: .Movies)
        Kids = try values.decodeIfPresent([ChannelList].self, forKey: .Kids)
        
        
    }
}

struct ChannelList : Codable {
    
    let genrename : [String]?
    var callSign:String?
    
    
    enum CodingKeys: String, CodingKey {
        
        
        case callSign = "callSign"
        case genrename = "genrename"
        
    }
    
    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        genrename = try values.decodeIfPresent([String].self, forKey: .genrename)
        callSign = try values.decodeIfPresent(String.self, forKey: .callSign)
        
        
    }
}



