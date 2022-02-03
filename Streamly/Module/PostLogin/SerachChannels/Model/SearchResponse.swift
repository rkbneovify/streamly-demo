//
//  SearchResponse.swift
//  Streamly
//
//  Created by Rohit Singh on 6/15/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation

struct ChannelResponse : Codable {
    
    let data : ChannelData?
    
    enum CodingKeys: String, CodingKey {
        
        case data = "data"
        
    }
    
    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        data = try values.decodeIfPresent(ChannelData.self, forKey: .data)
        
    }
}

struct ChannelData : Codable {
    
   // let current_page : Int?
    let data :   [ChannelArray]?
    let success: Bool?
    
    enum CodingKeys: String, CodingKey {
        
      //  case current_page = "current_page"
        case data = "data"
        case success = "success"
    }
    
    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        success = try values.decodeIfPresent(Bool.self, forKey: .success)
        data = try values.decodeIfPresent([ChannelArray].self, forKey: .data)
        
    }
    
    
}

struct ChannelArray : Codable {
    
    //  let connected_devices : Int?
    let id: Int?
    let callSign: String?
    let stationName: String?
    let image: String?
    let url: String?
    let genrename: [String]?
    let logo: String?
    
    enum CodingKeys: String, CodingKey {
        case id = "id"
        case callSign = "callSign"
        case stationName = "stationName"
        case image = "image"
        case url = "url"
        case genrename = "genrename"
        case logo = "logo"
    }
    
    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        //  connected_devices = try values.decodeIfPresent(Int.self, forKey: .connected_devices)
        stationName = try values.decodeIfPresent(String.self, forKey: .stationName)
        url = try values.decodeIfPresent(String.self, forKey: .url)
        logo = try values.decodeIfPresent(String.self, forKey: .logo)
        id = try values.decodeIfPresent(Int.self, forKey: .id)
        image = try values.decodeIfPresent(String.self, forKey: .image)
        callSign = try values.decodeIfPresent(String.self, forKey: .callSign)
        genrename = try values.decodeIfPresent([String].self, forKey: .genrename)
    }
    
}
